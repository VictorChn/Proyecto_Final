<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tu Cita ha sido Reagendada - GIO & ANGIE</title>
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
            padding: 30px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .content {
            padding: 40px 30px;
            line-height: 1.6;
            font-size: 15px;
        }
        .info-message {
            background-color: #f6ebff;
            border: 1px solid #e2ccf7;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
            color: #7b4c9c;
        }
        .details-box {
            background-color: #f8f5fa;
            border-left: 4px solid #c791e8;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }
        .details-item {
            margin-bottom: 10px;
        }
        .details-item:last-child {
            margin-bottom: 0;
        }
        .details-label {
            font-weight: bold;
            color: #c791e8;
            display: inline-block;
            width: 110px;
        }
        .footer {
            background-color: #fcfbfe;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #a69cb0;
            border-top: 1px solid #f5eef9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>GIO & ANGIE</h1>
        </div>
        <div class="content">
            <p>¡Hola, <strong>{{ $clientName }}</strong>!</p>
            
            <div class="info-message">
                Te informamos que tu cita en <strong>GIO & ANGIE</strong> ha sido reagendada con éxito a un nuevo horario.
            </div>
            
            <p>A continuación, te presentamos los nuevos detalles de tu reservación:</p>
            
            <div class="details-box">
                <div class="details-item">
                    <span class="details-label">Nueva Fecha:</span>
                    <span>{{ $date }}</span>
                </div>
                <div class="details-item">
                    <span class="details-label">Nueva Hora:</span>
                    <span>{{ $time }}</span>
                </div>
                <div class="details-item">
                    <span class="details-label">Servicio(s):</span>
                    <span>{{ $services }}</span>
                </div>
                <div class="details-item">
                    <span class="details-label">Monto Total:</span>
                    <span>${{ $totalPrice }}</span>
                </div>
            </div>

            <p>Te esperamos en el salón. Si necesitas realizar otro cambio o cancelar, por favor inicia sesión en nuestra plataforma con al menos 24 horas de anticipación. ¡Que tengas un excelente día!</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} GIO & ANGIE. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>
