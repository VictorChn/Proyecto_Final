<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Agenda Diaria - GIO & ANGIE / AuraSpa</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #2c1a36;
            margin: 0;
            padding: 20px;
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
        .info-card {
            background-color: #f8f5fa;
            border-left: 4px solid #c791e8;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 0 6px 6px 0;
        }
        .info-title {
            font-size: 14px;
            font-weight: bold;
            color: #2c1a36;
            margin-bottom: 5px;
        }
        .info-text {
            font-size: 12px;
            color: #666;
        }
        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .appointments-table th {
            background-color: #fcfbfe;
            color: #2c1a36;
            font-weight: bold;
            text-align: left;
            padding: 10px 12px;
            border-bottom: 2px solid #e2d5ec;
            font-size: 11px;
            text-transform: uppercase;
        }
        .appointments-table td {
            padding: 10px 12px;
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
            font-size: 11px;
            color: #888;
            margin-top: 2px;
        }
        .services-list {
            font-size: 12px;
            color: #555;
        }
        .duration-lbl {
            font-size: 10px;
            color: #888;
            margin-top: 2px;
        }
        .summary-section {
            text-align: right;
            font-size: 12px;
            color: #666;
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 15px;
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
        <div class="subtitle">AuraSpa &bull; Mi Agenda de Trabajo</div>
        <div class="report-title">Agenda Personal Diaria</div>
        <div class="report-date">Citas programadas para el: <strong>{{ $date }}</strong></div>
    </div>

    <!-- Información del Estilista -->
    <div class="info-card">
        <div class="info-title">{{ $specialist->user->name }}</div>
        <div class="info-text">
            Especialidad: <strong>{{ $specialist->specialty }}</strong> &bull; 
            Total citas asignadas para mañana: <strong>{{ $appointments->count() }}</strong>
        </div>
    </div>

    <!-- Listado de Citas -->
    <table class="appointments-table">
        <thead>
            <tr>
                <th style="width: 20%;">Hora</th>
                <th style="width: 35%;">Cliente</th>
                <th style="width: 45%;">Servicio(s) a Realizar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appt)
                <tr>
                    <td class="time-col">
                        {{ \Carbon\Carbon::parse($appt->time)->format('h:i A') }}
                    </td>
                    <td>
                        <div class="client-info">{{ $appt->client->name ?? 'Cliente Invitado' }}</div>
                        <div class="client-phone">Teléfono: {{ $appt->client->phone ?? 'Sin registro' }}</div>
                    </td>
                    <td>
                        <div class="services-list">
                            {{ $appt->services->pluck('name')->implode(', ') }}
                        </div>
                        <div class="duration-lbl">
                            Duración estimada: {{ $appt->services->sum('duration') }} minutos
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-section">
        Total Horas/Minutos Estimados: <strong>{{ $appointments->sum(fn($a) => $a->services->sum('duration')) }} minutos</strong> de servicio.
    </div>

    <div class="footer">
        <p>GIO & ANGIE / AuraSpa &bull; Documento de Trabajo Interno &bull; Generado el {{ date('d/m/Y h:i A') }}</p>
    </div>

</body>
</html>
