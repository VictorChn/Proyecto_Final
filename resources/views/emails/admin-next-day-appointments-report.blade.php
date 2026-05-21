<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Consolidado Diario de Citas - GIO & ANGIE / AuraSpa</title>
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
            margin-bottom: 15px;
        }
        .kpis-container {
            margin: 25px 0;
            text-align: center;
        }
        .kpi-card {
            display: inline-block;
            width: 28%;
            background-color: #f8f5fa;
            border: 1px solid #e2d5ec;
            border-radius: 8px;
            padding: 15px 5px;
            margin: 0 1%;
            text-align: center;
            vertical-align: top;
        }
        .kpi-val {
            font-size: 20px;
            font-weight: bold;
            color: #c791e8;
            margin-bottom: 5px;
        }
        .kpi-lbl {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
            <p>AURASPA &bull; ADMINISTRACIÓN</p>
        </div>
        <div class="content">
            <h2 class="welcome-title">¡Hola, Administrador!</h2>
            <p>Se ha generado de forma automática el consolidado de citas para el día de mañana, <strong>{{ $date }}</strong>. Adjunto a este correo electrónico encontrarás el documento PDF con el reporte completo y detallado, agrupado por estilista.</p>
            
            <p>A continuación te compartimos un resumen rápido con los indicadores clave del día de mañana:</p>
            
            <div class="kpis-container">
                <div class="kpi-card">
                    <div class="kpi-val">{{ $totalAppointments }}</div>
                    <div class="kpi-lbl">Total Citas</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-val">${{ number_format($totalRevenue, 2) }}</div>
                    <div class="kpi-lbl">Ingresos Est.</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-val">{{ $totalStylists }}</div>
                    <div class="kpi-lbl">Estilistas</div>
                </div>
            </div>

            <p>Para ver y gestionar todas las citas del salón en tiempo real o realizar ajustes, puedes acceder directamente a la plataforma de administración haciendo clic en el siguiente enlace:</p>

            <div class="button-container">
                <a href="{{ route('dashboard') }}" class="btn-premium">Ir al Panel de Administración</a>
            </div>

            <p>El reporte completo en PDF ha sido adjuntado a este correo electrónico para su fácil consulta o impresión.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} GIO & ANGIE / AuraSpa &bull; Panel de Control. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
