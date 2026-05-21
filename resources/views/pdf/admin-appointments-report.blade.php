<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Consolidado de Citas - GIO & ANGIE / AuraSpa</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #2c1a36;
            margin: 0;
            padding: 10px;
            font-size: 13px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #c791e8;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #c791e8;
            margin: 0;
            letter-spacing: 2px;
        }
        .subtitle {
            color: #666;
            font-size: 11px;
            margin-top: 3px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 15px;
            color: #2c1a36;
        }
        .report-date {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }
        .kpis-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .kpi-box {
            background-color: #f8f5fa;
            border: 1px solid #e2d5ec;
            border-radius: 6px;
            padding: 12px;
            text-align: center;
            width: 31%;
        }
        .kpi-val {
            font-size: 18px;
            font-weight: bold;
            color: #c791e8;
        }
        .kpi-lbl {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            margin-top: 3px;
        }
        .stylist-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .stylist-header {
            background-color: #f8f5fa;
            border-left: 4px solid #c791e8;
            padding: 8px 12px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .stylist-name {
            font-size: 14px;
            color: #2c1a36;
        }
        .stylist-specialty {
            font-size: 11px;
            color: #888;
            font-weight: normal;
        }
        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .appointments-table th {
            background-color: #fcfbfe;
            color: #2c1a36;
            font-weight: bold;
            text-align: left;
            padding: 8px 10px;
            border-bottom: 2px solid #e2d5ec;
            font-size: 11px;
            text-transform: uppercase;
        }
        .appointments-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }
        .time-col {
            font-weight: bold;
            color: #c791e8;
            white-space: nowrap;
        }
        .client-info {
            font-weight: bold;
        }
        .client-phone {
            font-size: 10px;
            color: #888;
            margin-top: 2px;
        }
        .services-list {
            font-size: 11px;
            color: #555;
        }
        .price-col {
            text-align: right;
            font-weight: bold;
        }
        .stylist-summary {
            text-align: right;
            font-size: 11px;
            color: #666;
            margin-top: 5px;
            padding-right: 10px;
        }
        .no-appointments {
            padding: 15px;
            text-align: center;
            color: #888;
            font-style: italic;
            border: 1px dashed #ddd;
            border-radius: 6px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #aaa;
            border-top: 1px solid #eee;
            padding-top: 10px;
            height: 30px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="logo">GIO & ANGIE</h1>
        <div class="subtitle">AuraSpa &bull; Reporte Consolidado de Agenda</div>
        <div class="report-title">Agenda Diaria de Citas</div>
        <div class="report-date">Citas programadas para el: <strong>{{ $date }}</strong></div>
    </div>

    <!-- KPIs del Día -->
    <table class="kpis-table" style="width: 100%;">
        <tr>
            <td class="kpi-box">
                <div class="kpi-val">{{ $totalAppointments }}</div>
                <div class="kpi-lbl">Total Citas</div>
            </td>
            <td style="width: 3.5%;"></td>
            <td class="kpi-box">
                <div class="kpi-val">${{ number_format($totalRevenue, 2) }}</div>
                <div class="kpi-lbl">Ingresos Estimados</div>
            </td>
            <td style="width: 3.5%;"></td>
            <td class="kpi-box">
                <div class="kpi-val">{{ $totalStylists }}</div>
                <div class="kpi-lbl">Estilistas Asignados</div>
            </td>
        </tr>
    </table>

    <!-- Listado por Estilista -->
    @foreach($stylistsData as $data)
        <div class="stylist-section">
            <div class="stylist-header">
                <span class="stylist-name">{{ $data['name'] }}</span>
                <span class="stylist-specialty">&bull; {{ $data['specialty'] }}</span>
            </div>

            @if($data['appointments']->count() > 0)
                <table class="appointments-table">
                    <thead>
                        <tr>
                            <th style="width: 15%;">Hora</th>
                            <th style="width: 30%;">Cliente</th>
                            <th style="width: 40%;">Servicios</th>
                            <th style="width: 15%; text-align: right;">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['appointments'] as $appt)
                            <tr>
                                <td class="time-col">
                                    {{ \Carbon\Carbon::parse($appt->time)->format('h:i A') }}
                                </td>
                                <td>
                                    <div class="client-info">{{ $appt->client->name ?? 'Invitado' }}</div>
                                    <div class="client-phone">Tel: {{ $appt->client->phone ?? 'Sin registro' }}</div>
                                </td>
                                <td>
                                    <div class="services-list">
                                        {{ $appt->services->pluck('name')->implode(', ') }}
                                    </div>
                                    <div style="font-size: 10px; color: #888; margin-top: 2px;">
                                        Duración: {{ $appt->services->sum('duration') }} min
                                    </div>
                                </td>
                                <td class="price-col">
                                    ${{ number_format($appt->services->sum('price'), 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="stylist-summary">
                    Total Citas: <strong>{{ $data['appointments']->count() }}</strong> &bull; Subtotal Estimado: <strong>${{ number_format($data['revenue'], 2) }}</strong>
                </div>
            @else
                <div class="no-appointments">
                    No cuenta con citas programadas para el día de mañana.
                </div>
            @endif
        </div>
    @endforeach

    <div class="footer">
        <p>GIO & ANGIE / AuraSpa &bull; Reporte de Administración Interna &bull; Generado el {{ date('d/m/Y h:i A') }}</p>
    </div>

</body>
</html>
