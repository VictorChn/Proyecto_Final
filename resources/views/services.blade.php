<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios - {{ config('app.name', 'GIO & ANGIE') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://kit.fontawesome.com/d06f8df15d.js" crossorigin="anonymous"></script>
</head>
<body class="bg-[#F6EBFF] text-[#2c1a36] font-sans antialiased min-h-screen flex flex-col">
    <!-- Navbar -->
    <header class="w-full bg-white shadow-sm">
        <div class="max-w-7xl mx-auto mt-4 mb-6 px-4 md:px-6 lg:px-8 flex items-center justify-between lg:my-5">
            <a href="{{ url('/') }}" class="flex items-center gap-3 hover:opacity-80 transition">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-16 sm:w-20 lg:w-24 rounded-full">
            </a>
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
        </div>
    </header>

    <!-- Header / Hero Services -->
    <div class="bg-[#c791e8] py-16 text-center text-white">
        <h1 class="text-4xl md:text-5xl font-bold mb-4 uppercase tracking-wide">Nuestros Servicios</h1>
        <p class="text-lg md:text-xl max-w-2xl mx-auto px-4">Descubre todas las opciones que tenemos para resaltar tu belleza. Selecciona tu servicio ideal y agenda tu cita hoy mismo.</p>
    </div>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="mb-16">
            <div class="flex items-center gap-4 mb-6 border-b-2 border-[#c791e8] pb-4">
                    <div class="bg-[#a66cc9] text-white p-4 rounded-lg shadow-md">
                        <i class="fa-solid fa-scissors text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-[#a66cc9]">Gio Salón — Estilismo y Cabello</h2>
                        <p class="text-gray-600 mt-1">Servicios profesionales de corte, colorimetría y tratamientos para el cuidado de tu cabello.</p>
                    </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
                @foreach($services->where('category', 'Estilismo') as $service)
                    <x-service-card :service="$service" />
                @endforeach
            </div>


            <div class="flex items-center gap-4 mb-6 border-b-2 border-[#c791e8] pb-4">
                    <div class="bg-[#a66cc9] text-white p-4 rounded-lg shadow-md">
                        <i class="fa-solid fa-hand-sparkles text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-[#a66cc9]">Angie Nails — Manos y Pies</h2>
                        <p class="text-gray-600 mt-1">Especialistas en extensiones de uñas, esmaltado semipermanente y cuidado de la piel.</p>
                    </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($services->where('category', 'Pedicura') as $service)
                    <x-service-card :service="$service" />
                @endforeach
            </div>
        </div>
        

        

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} GIO & ANGIE Salón de Belleza. Todos los derechos reservados.
        </div>
    </footer>
</body>
</html>