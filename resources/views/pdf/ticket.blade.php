<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Cita - AuraSpa</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #2c1a36;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #c791e8;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #c791e8;
            margin: 0;
            letter-spacing: 2px;
        }
        .subtitle {
            color: #666;
            font-size: 12px;
            margin-top: 5px;
        }
        .info-section {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            color: #888;
            font-size: 11px;
            text-transform: uppercase;
        }
        .info-value {
            font-size: 14px;
            font-weight: bold;
            color: #2c1a36;
        }
        .services-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .services-table th {
            background-color: #f8f5fa;
            color: #2c1a36;
            font-weight: bold;
            text-align: left;
            padding: 12px;
            border-bottom: 2px solid #c791e8;
            font-size: 12px;
            text-transform: uppercase;
        }
        .services-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        .total-section {
            width: 100%;
            text-align: right;
            margin-top: 20px;
        }
        .total-box {
            display: inline-block;
            background-color: #f8f5fa;
            padding: 15px 30px;
            border-radius: 5px;
            border: 1px solid #e2d5ec;
        }
        .total-label {
            font-size: 14px;
            color: #666;
            margin-right: 15px;
        }
        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #c791e8;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 11px;
            color: #aaa;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="logo">AuraSpa</h1>
        <div class="subtitle">Centro de Estética Integral y Belleza</div>
        <div style="margin-top: 10px; font-size: 12px; color: #888;">
            Comprobante de Atención N° #{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}
        </div>
    </div>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td style="width: 50%;">
                    <div class="info-label">Cliente</div>
                    <div class="info-value">{{ $appointment->client->name ?? 'No registrado' }}</div>
                </td>
                <td style="width: 50%; text-align: right;">
                    <div class="info-label">Fecha de Atención</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($appointment->scheduled_date)->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</div>
                </td>
            </tr>
            <tr>
                <td style="padding-top: 15px;">
                    <div class="info-label">Especialista / Estilista</div>
                    <div class="info-value">{{ $appointment->specialist->user->name ?? 'No designada' }}</div>
                </td>
                <td style="padding-top: 15px; text-align: right;">
                    <div class="info-label">Estado de Cita</div>
                    <div class="info-value" style="color: #27ae60;">COMPLETADA</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="services-table">
        <thead>
            <tr>
                <th>Servicio / Tratamiento</th>
                <th style="text-align: center;">Duración</th>
                <th style="text-align: right;">Precio</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalPrice = 0; 
                $totalDuration = 0;
            @endphp
            @foreach($appointment->services as $service)
                @php 
                    $totalPrice += $service->price; 
                    $totalDuration += $service->duration;
                @endphp
                <tr>
                    <td style="font-weight: bold; color: #444;">{{ $service->name }}</td>
                    <td style="text-align: center; color: #888;">{{ $service->duration }} min</td>
                    <td style="text-align: right; font-weight: bold;">${{ number_format($service->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <div style="margin-bottom: 10px; color: #888; font-size: 12px;">
            Duración total del servicio: <strong>{{ $totalDuration }} minutos</strong>
        </div>
        <div class="total-box">
            <span class="total-label">Total Pagado:</span>
            <span class="total-amount">${{ number_format($totalPrice, 2) }}</span>
        </div>
    </div>

    <div class="footer">
        <p>Gracias por confiar en AuraSpa para tu cuidado personal.</p>
        <p>Este documento es un comprobante de servicio y no tiene validez como factura fiscal.</p>
        <p style="margin-top: 5px; color: #ccc;">Generado automáticamente el {{ date('d/m/Y H:i:s') }}</p>
    </div>

</body>
</html>
