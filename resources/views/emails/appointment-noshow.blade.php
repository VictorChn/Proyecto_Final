<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lamentamos tu inasistencia - GIO & ANGIE / AuraSpa</title>
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
            background-color: #e57373; /* Un color cálido/coral que expresa empatía */
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
            margin-bottom: 15px;
        }
        .details-box {
            background-color: #fff8f8;
            border-left: 4px solid #e57373;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }
        .details-item {
            margin-bottom: 10px;
            font-size: 14px;
        }
        .details-item:last-child {
            margin-bottom: 0;
        }
        .details-label {
            font-weight: bold;
            color: #e57373;
            display: inline-block;
            width: 120px;
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
        .social-notice {
            margin-top: 15px;
            font-size: 11px;
            color: #c0b7c7;
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
            <h2 class="welcome-title">¡Hola, {{ $clientName }}!</h2>
            <p>Lamentamos mucho que no hayas podido asistir a tu cita programada para el día de hoy. Esperamos sinceramente que todo se encuentre bien y que tu ausencia haya sido por un tema de fuerza mayor que pudiste resolver.</p>
            
            <p>Los detalles de la cita agendada eran:</p>
            
            <div class="details-box">
                <div class="details-item">
                    <span class="details-label">Fecha:</span>
                    <span>{{ $date }}</span>
                </div>
                <div class="details-item">
                    <span class="details-label">Hora:</span>
                    <span>{{ $time }}</span>
                </div>
                <div class="details-item">
                    <span class="details-label">Servicio(s):</span>
                    <span>{{ $services }}</span>
                </div>
            </div>

            <p>No te preocupes, en <strong>GIO & ANGIE / AuraSpa</strong> entendemos perfectamente que a veces surgen imprevistos. Te animamos a programar un nuevo espacio en nuestra agenda en el horario que mejor te acomode. ¡Nos encantaría consentirte muy pronto!</p>

            <div class="button-container">
                <a href="{{ route('dashboard') }}" class="btn-premium">Agendar una Nueva Cita</a>
            </div>

            <p>Si tuviste algún inconveniente con el sistema o necesitas asistencia especial para agendar tu cita, no dudes en ponerte en contacto con nosotros.</p>
            
            <p>¡Esperamos verte muy pronto en nuestro salón!</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} GIO & ANGIE / AuraSpa. Todos los derechos reservados.</p>
            <p class="social-notice">Este es un correo informativo sobre tu cita. Por favor, no respondas a este mensaje.</p>
        </div>
    </div>
</body>
</html>
