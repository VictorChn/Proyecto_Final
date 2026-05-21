<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cancelación de Cita - GIO & ANGIE</title>
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
            background-color: #e57373; /* Red-ish shade for cancellation */
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
            background-color: #ffebee;
            border: 1px solid #ffcdd2;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
            color: #c62828;
        }
        .details-box {
            background-color: #f8f5fa;
            border-left: 4px solid #e57373;
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
            color: #e57373;
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
            <p>¡Hola, <strong>{{ $recipientName }}</strong>!</p>
            
            <div class="info-message">
                @if($recipientType === 'client')
                    Te confirmamos que tu cita en <strong>GIO & ANGIE</strong> ha sido <strong>cancelada</strong> correctamente.
                @else
                    Te notificamos que la cita asignada contigo ha sido <strong>cancelada</strong> por el cliente.
                @endif
            </div>
            
            <p>A continuación se presentan los detalles de la cita cancelada:</p>
            
            <div class="details-box">
                <div class="details-item">
                    <span class="details-label">Cliente:</span>
                    <span>{{ $clientName }}</span>
                </div>
                <div class="details-item">
                    <span class="details-label">Estilista:</span>
                    <span>{{ $stylistName }}</span>
                </div>
                <div class="details-item">
                    <span class="details-label">Fecha original:</span>
                    <span>{{ $date }}</span>
                </div>
                <div class="details-item">
                    <span class="details-label">Hora original:</span>
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

            @if($recipientType === 'client')
                <p>Lamentamos que hayas tenido que cancelar tu cita. Esperamos poder atenderte pronto. ¡No dudes en programar una nueva reservación cuando lo desees!</p>
            @else
                <p>Tu agenda ha sido liberada para este horario y está disponible para nuevas reservaciones. ¡Que tengas un excelente día de trabajo!</p>
            @endif
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} GIO & ANGIE. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>
