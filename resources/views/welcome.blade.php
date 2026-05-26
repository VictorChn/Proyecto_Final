<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        @fonts

        <!-- PWA Meta Tags & Manifest -->
        <meta name="theme-color" content="#2c1a36">
        <link rel="manifest" href="/manifest.json">
        <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#F6EBFF] text-[#2c1a36]">
        <header class="mt-4 lg:mt-6 mx-auto w-full flex items-center justify-between lg:max-w-5xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden ">
            <div>
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-18 lg:w-24 rounded-full">
            </div>
            @if (Route::has('login'))
                <nav class="flex justify-end items-center gap-4">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="inline-block px-5 py-1.5 border-[#19140035] hover:border-[#1915014a] border text-[#2c1a36] rounded-sm text-sm leading-normal"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="inline-block px-5 py-1.5 text-[#1b1b18] border-2 border-[#19140035] hover:border-[#a66cc9] hover:scale-110 transition-transform hover:text-[#a66cc9] rounded-sm text-sm leading-normal"
                        >
                            Iniciar Sesión
                        </a>
                    @endauth
                </nav>
            @endif
        </header>

        <div class="w-full bg-cover bg-no-repeat bg-center flex flex-col items-center justify-center text-[#F6EBFF] py-12 px-6 md:p-24" style="background-image: url('{{ asset('img/FondoHero.webp') }}');">
            <h1 class="text-2xl md:text-4xl font-bold text-center mb-7 uppercase">Tu belleza en manos de expertas, <br> a un solo clic de distancia.</h1>
            <p class="text-center w-full md:w-1/2">En Salón de Belleza GIO & ANGIE diseñamos un espacio exclusivo para consentirte. Elige tus servicios favoritos de estilismo y diseño de uñas, agenda tu cita en segundos y recibe recordatorios automáticos directamente en tu teléfono. Tu momento de bienestar te espera.</p>

            <div class="mt-10 flex flex-col sm:flex-row gap-4 items-center">
                <a href="{{ route('register') }}" class="px-6 py-3 bg-[#c791e8] text-white font-semibold rounded-md hover:bg-[#a66cc9] transition text-center w-full sm:w-auto">Agenda tu Cita</a>
                <a href="{{ route('services') }}" class="px-6 py-3 border-2 border-[#c791e8] text-[#F6EBFF] font-semibold rounded-md hover:bg-[#c791e8] hover:text-white transition text-center w-full sm:w-auto">Ver Servicios</a>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row mx-auto lg:max-w-5xl my-10 justify-between gap-5 px-4 lg:px-0">
            <div class="w-full lg:w-1/3 px-5 py-7 border text-center shadow-md rounded-md shadow-[#a66cc9] bg-[#f8f5fa]">
                <i class="fa-solid fa-hand-point-left text-7xl text-[#a66cc9] mb-5"></i>
                <h2 class="text-[#a66cc9] text-xl md:text-2xl font-mono font-bold mb-3 uppercase">Elige tu Servicio</h2>
                <p>Explora nuestro catálogo de estilismo, cortes, tintes y manicura, pedicura o sistemas de uñas.</p>
            </div>

            <div class="w-full lg:w-1/3 px-5 py-7 border text-center shadow-md rounded-md shadow-[#a66cc9] bg-[#f8f5fa]">
                <i class="fa-solid fa-calendar-check text-7xl text-[#a66cc9] mb-5"></i>
                <h2 class="text-[#a66cc9] text-xl md:text-2xl font-mono font-bold mb-3 uppercase">Selecciona Fecha y Hora</h2>
                <p>Visualiza en tiempo real los horarios disponibles de nuestras especialistas y reserva el que mejor se adapte a ti.</p>
            </div>

            <div class="w-full lg:w-1/3 px-5 py-7 border text-center shadow-md rounded-md shadow-[#a66cc9] bg-[#f8f5fa]">
                <i class="fa-solid fa-bell text-7xl text-[#a66cc9] mb-5"></i>
                <h2 class="text-[#a66cc9] text-xl md:text-2xl font-mono font-bold mb-3 uppercase">¡Listo, nosotros te avisamos!</h2>
                <p>Recibirás una notificación instantánea y un recordatorio automático antes de tu cita para que no tengas que preocuparte por nada.</p>
            </div>
        </div>

        <footer class="bg-white border-t py-8 mt-auto">
            <div class="max-w-7xl mx-auto px-4 text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} GIO & ANGIE Salón de Belleza. Todos los derechos reservados.
            </div>
        </footer>
                <!-- ═══ KOMMUNICATE CHATBOT WIDGET ═══ -->
        <script type="text/javascript">
            (function(d, m){
                var kommunicateSettings = 
                    {"appId":"35f74852ff3a2dde1de97178cf7148e56","popupWidget":true,"automaticChatOpenOnNavigation":true};
                var s = document.createElement("script"); s.type = "text/javascript"; s.async = true;
                s.src = "https://widget.kommunicate.io/kommunicate-widget-3.0.min.js";
                var h = document.getElementsByTagName("head")[0]; h.appendChild(s);
                window.kommunicate = m; m._globals = kommunicateSettings;
            })(document, window.kommunicate || {});
        </script>

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
        <script src="https://kit.fontawesome.com/d06f8df15d.js" crossorigin="anonymous"></script>
    </body>
</html>
