<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tu Agenda Diaria de Citas - GIO & ANGIE / AuraSpa</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8f5fa;
            color: #2c1a36;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(44, 26, 54, 0.05);
            border: 1px solid #f0e6f5;
        }
        .header {
            background-color: #c791e8;
            padding: 35px 30px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
            letter-spacing: 1px;
        }
        .content {
            padding: 40px 30px;
            line-height: 1.6;
            font-size: 15px;
        }
        .welcome-title {
            color: #2c1a36;
            font-size: 20px;
            font-weight: bold;
            margin-top: 0;
            margin-bottom: 10px;
        }
        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
        }
        .appointments-table th {
            background-color: #f8f5fa;
            color: #2c1a36;
            font-weight: bold;
            text-align: left;
            padding: 12px;
            border-bottom: 2px solid #c791e8;
            font-size: 12px;
            text-transform: uppercase;
        }
        .appointments-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
            font-size: 14px;
        }
        .time-badge {
            color: #c791e8;
            font-weight: bold;
            white-space: nowrap;
        }
        .client-name {
            font-weight: bold;
        }
        .client-phone {
            font-size: 11px;
            color: #888;
            margin-top: 3px;
        }
        .services-list {
            font-size: 12px;
            color: #666;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .btn-premium {
            background-color: #c791e8;
            color: #ffffff !important;
            padding: 14px 28px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 30px;
            display: inline-block;
            box-shadow: 0 4px 10px rgba(199, 145, 232, 0.3);
            transition: all 0.3s ease;
            font-size: 14px;
            letter-spacing: 0.5px;
        }
        .btn-premium:hover {
            background-color: #b57dd6;
            box-shadow: 0 6px 14px rgba(199, 145, 232, 0.4);
            transform: translateY(-2px);
        }
        .footer {
            background-color: #fcfbfe;
            padding: 25px 20px;
            text-align: center;
            font-size: 12px;
            color: #a69cb0;
            border-top: 1px solid #f5eef9;
        }
        .footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>GIO & ANGIE</h1>
            <p>AURASPA</p>
        </div>
        <div class="content">
            <h2 class="welcome-title">¡Hola, {{ $stylistName }}!</h2>
            <p>Te compartimos tu agenda de citas programadas para el día de mañana, <strong>{{ $date }}</strong>. Prepárate para consentir a nuestros clientes y ofrecerles una experiencia del más alto nivel.</p>
            
            <table class="appointments-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">Hora</th>
                        <th style="width: 35%;">Cliente</th>
                        <th style="width: 40%;">Servicio(s)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appt)
                        <tr>
                            <td class="time-badge">
                                {{ \Carbon\Carbon::parse($appt->time)->format('g:i A') }}
                            </td>
                            <td>
                                <div class="client-name">{{ $appt->client->name ?? 'Cliente Invitado' }}</div>
                                <div class="client-phone">Tel: {{ $appt->client->phone ?? 'Sin registro' }}</div>
                            </td>
                            <td class="services-list">
                                {{ $appt->services->pluck('name')->implode(', ') }}
                                <div style="font-size: 10px; color: #888; margin-top: 3px;">
                                    Duración: {{ $appt->services->sum('duration') }} min
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p>Recuerda que puedes ver más detalles de tu agenda y gestionar la asistencia de las citas directamente en tu panel de estilista haciendo clic en el siguiente enlace:</p>

            <div class="button-container">
                <a href="{{ route('stylist.agenda') }}" class="btn-premium">Ver Mi Agenda Completa</a>
            </div>

            <p>¡Que tengas una excelente y muy productiva jornada el día de mañana!</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} GIO & ANGIE / AuraSpa. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
