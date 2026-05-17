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
        <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8 flex items-center justify-between lg:my-5">
            <a href="{{ url('/') }}" class="flex items-center gap-3 hover:opacity-80 transition">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-24 rounded-full">
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
        
        <?php
        $categories = [
            [
                'name' => 'Gio Salón — Estilismo y Cabello',
                'icon' => 'fa-scissors',
                'description' => 'Servicios profesionales de corte, colorimetría y tratamientos para el cuidado de tu cabello.',
                'services' => [
                    [
                        'name' => 'Corte de Cabello Dama',
                        'price' => 180.00,
                        'duration' => '45 min',
                        'description' => 'Incluye lavado, tratamiento hidratante exprés y estilizado final.'
                    ],
                    [
                        'name' => 'Tinte Global / Cambio de Tono',
                        'price' => 650.00,
                        'duration' => '120 min',
                        'description' => 'Aplicación de color uniforme con productos de alta gama para proteger tu fibra capilar.'
                    ],
                    [
                        'name' => 'Efectos de Color (Balayage / Mechas)',
                        'price' => 1300.00,
                        'duration' => '180 min',
                        'description' => 'Diseño de aclaración personalizado. Requiere diagnóstico previo en el salón.'
                    ],
                    [
                        'name' => 'Tratamiento de Keratina Alaciante',
                        'price' => 980.00,
                        'duration' => '150 min',
                        'description' => 'Elimina el frizz por completo, aporta brillo espejo y un lacio natural.'
                    ],
                    [
                        'name' => 'Peinado y Maquillaje Social',
                        'price' => 750.00,
                        'duration' => '90 min',
                        'description' => 'Paquete completo para eventos sociales con productos de larga duración.'
                    ]
                ]
            ],
            [
                'name' => 'Angie Nails — Manos y Pies',
                'icon' => 'fa-hand-sparkles',
                'description' => 'Especialistas en extensiones de uñas, esmaltado semipermanente y cuidado de la piel.',
                'services' => [
                    [
                        'name' => 'Esmaltado en Gelish (Manos)',
                        'price' => 160.00,
                        'duration' => '40 min',
                        'description' => 'Aplicación de color semipermanente en uña natural con una amplia variedad de tonos.'
                    ],
                    [
                        'name' => 'Uñas Acrílicas (Set Nuevo Básico)',
                        'price' => 380.00,
                        'duration' => '90 min',
                        'description' => 'Estructura en tamaño corto o mediano con esmaltado liso de tu elección.'
                    ],
                    [
                        'name' => 'Uñas Acrílicas con Diseño Completo',
                        'price' => 480.00,
                        'duration' => '120 min',
                        'description' => 'Incluye efectos (espejo, ojo de gato), cristales o arte pintado a mano alzada.'
                    ],
                    [
                        'name' => 'Manicura Rusa Combinada',
                        'price' => 220.00,
                        'duration' => '50 min',
                        'description' => 'Limpieza profunda de cutículas con torno para un acabado limpio y duradero.'
                    ],
                    [
                        'name' => 'Pedicura Spa Premium',
                        'price' => 320.00,
                        'duration' => '60 min',
                        'description' => 'Tina de hidromasaje, exfoliación, remoción de callosidades, mascarilla e hidratación.'
                    ]
                ]
            ]
        ];
        ?>

        @foreach($categories as $category)
            <div class="mb-16">
                <!-- Category Header -->
                <div class="flex items-center gap-4 mb-6 border-b-2 border-[#c791e8] pb-4">
                    <div class="bg-[#a66cc9] text-white p-4 rounded-lg shadow-md">
                        <i class="fa-solid {{ $category['icon'] }} text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-[#a66cc9]">{{ $category['name'] }}</h2>
                        <p class="text-gray-600 mt-1">{{ $category['description'] }}</p>
                    </div>
                </div>

                <!-- Services Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($category['services'] as $service)
                        <div class="bg-white rounded-xl shadow-md border border-[#f0dfff] overflow-hidden hover:shadow-lg hover:border-[#c791e8] transition-all flex flex-col h-full group">
                            <div class="p-6 flex-grow">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-xl font-bold text-[#2c1a36] leading-tight pr-4">{{ $service['name'] }}</h3>
                                    <span class="bg-[#F6EBFF] text-[#a66cc9] font-bold px-3 py-1 rounded-full whitespace-nowrap border border-[#c791e8] group-hover:bg-[#a66cc9] group-hover:text-white transition-colors">
                                        ${{ number_format($service['price'], 2) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500 mb-4 flex items-center gap-2 font-medium">
                                    <i class="fa-regular fa-clock text-[#c791e8]"></i> {{ $service['duration'] }}
                                </p>
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    {{ $service['description'] }}
                                </p>
                            </div>
                            <div class="p-4 bg-[#faf5ff] border-t border-[#f0dfff]">
                                <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2 bg-[#a66cc9] text-white font-semibold rounded-md hover:bg-[#8e52b1] transition-colors shadow-sm">
                                    Agendar Cita
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} GIO & ANGIE Salón de Belleza. Todos los derechos reservados.
        </div>
    </footer>
</body>
</html>