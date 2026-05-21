<div class="text-[#2c1a36]" wire:poll.60s>
    
    <!-- Header with Navigation and Toggle -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="font-bold text-2xl tracking-tight flex items-center gap-2">
                <i class="fa-regular fa-calendar-days text-[#c791e8]"></i>
                Mi Agenda
            </h1>
            <p class="text-xs text-gray-500 mt-1 capitalize">{{ $periodTitle }}</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-4">
            
            <!-- View Toggle -->
            <div class="bg-gray-100 p-1 rounded-lg flex items-center text-xs font-bold">
                <button wire:click="setViewMode('week')" class="px-4 py-1.5 rounded-md transition {{ $viewMode === 'week' ? 'bg-white shadow-sm text-[#c791e8]' : 'text-gray-500 hover:text-gray-700' }}">
                    Semana
                </button>
                <button wire:click="setViewMode('month')" class="px-4 py-1.5 rounded-md transition {{ $viewMode === 'month' ? 'bg-white shadow-sm text-[#c791e8]' : 'text-gray-500 hover:text-gray-700' }}">
                    Mes
                </button>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex items-center gap-2">
                <button wire:click="goToToday" class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 text-gray-600 text-xs font-bold transition shadow-sm">
                    Hoy
                </button>
                <div class="flex bg-white border border-gray-200 rounded-lg shadow-sm">
                    <button wire:click="previousPeriod" class="p-2 hover:bg-gray-50 text-gray-600 transition border-r border-gray-200 rounded-l-lg">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>
                    <button wire:click="nextPeriod" class="p-2 hover:bg-gray-50 text-gray-600 transition rounded-r-lg">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- CALENDARS -->
    @if($viewMode === 'week')
        
        <!-- TIME-GRID (Semanal) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden flex flex-col">
            
            <!-- Headers: Days of the week -->
            <div class="flex border-b border-gray-100 bg-[#f8f5fa]">
                <div class="w-16 flex-shrink-0 border-r border-gray-100"></div> <!-- Empty corner -->
                <div class="flex-1" style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr));">
                    @foreach($weeklyData as $day)
                        @php $isSelected = $selectedDate === $day['date']; @endphp
                        <div wire:click="selectDate('{{ $day['date'] }}')" 
                             class="py-3 px-1 text-center cursor-pointer transition {{ $isSelected ? 'bg-purple-50/50' : 'hover:bg-gray-50' }}">
                            <div class="text-[10px] font-bold uppercase tracking-wider {{ $isSelected ? 'text-[#c791e8]' : 'text-gray-400' }}">
                                {{ mb_substr($day['dayName'], 0, 3) }}
                            </div>
                            <div class="mt-1 flex justify-center">
                                <span class="w-7 h-7 flex items-center justify-center rounded-full text-sm font-black {{ $day['isToday'] ? 'bg-[#c791e8] text-white' : ($isSelected ? 'text-[#c791e8]' : 'text-gray-700') }}">
                                    {{ $day['dayNumber'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Body: Time Grid -->
            <div class="flex relative h-[600px] overflow-y-auto">
                <!-- Horas (Eje Y) -->
                <div class="w-16 flex-shrink-0 border-r border-gray-100 bg-white relative">
                    @for($h = 8; $h <= 20; $h++)
                        <div class="h-20 border-b border-gray-50 relative">
                            <span class="absolute -top-2.5 right-2 text-[10px] font-semibold text-gray-400 bg-white px-1">
                                {{ $h > 12 ? $h - 12 : $h }} {{ $h >= 12 ? 'PM' : 'AM' }}
                            </span>
                        </div>
                    @endfor
                </div>

                <!-- Columnas de los Días -->
                <div class="flex-1 divide-x divide-gray-100 relative bg-white" style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr));">
                    
                    <!-- Línea de Tiempo Real (Current Time Indicator) -->
                    @php
                        $now = \Carbon\Carbon::now();
                        $currentHour = $now->format('H');
                        $currentMin = $now->format('i');
                        // Mostrar la línea si estamos entre las 8:00 y las 20:59
                        $showTimeLine = ($currentHour >= 8 && $currentHour <= 20);
                        // Altura de cada bloque de hora es 5rem (h-20 en tailwind = 80px)
                        $topPixels = (($currentHour - 8) * 80) + (($currentMin / 60) * 80);
                    @endphp
                    
                    @if($showTimeLine)
                    <div class="absolute w-full z-20 pointer-events-none flex items-center" style="top: {{ $topPixels }}px;">
                        <div class="w-2 h-2 rounded-full bg-red-500 -ml-1"></div>
                        <div class="flex-1 border-t-2 border-red-500"></div>
                    </div>
                    @endif

                    <!-- Render Citas por Día -->
                    @foreach($weeklyData as $day)
                        <div class="relative h-full">
                            <!-- Background grid lines for half hours -->
                            @for($h = 8; $h <= 20; $h++)
                                <div class="h-20 border-b border-gray-50 box-border"></div>
                            @endfor

                            <!-- Citas (Bloques Absolutos) -->
                            @foreach($day['appointments'] as $app)
                                @php
                                    $time = \Carbon\Carbon::parse($app->time);
                                    $appHour = $time->format('H');
                                    $appMin = $time->format('i');
                                    
                                    // Validar que esté dentro de las 8 a 20hrs
                                    if ($appHour < 8) $appHour = 8;
                                    
                                    $startPixels = (($appHour - 8) * 80) + (($appMin / 60) * 80);
                                    
                                    // Total duración
                                    $totalDuration = $app->services->sum('duration');
                                    // Mínimo visual 30 mins para que no se vea aplastado
                                    $visualDuration = max($totalDuration, 30);
                                    $heightPixels = ($visualDuration / 60) * 80;
                                @endphp

                                <div class="absolute left-1 right-1 rounded-md bg-[#F6EBFF] border border-[#c791e8] border-l-4 p-1.5 overflow-hidden z-10 hover:shadow-md transition cursor-pointer group"
                                     style="top: {{ $startPixels }}px; height: {{ $heightPixels }}px;"
                                     wire:click="selectDate('{{ $day['date'] }}')"
                                     title="Cliente: {{ $app->client->name ?? 'Desconocido' }} | {{ $totalDuration }} mins">
                                    <div class="text-[9px] font-bold text-purple-900 leading-tight line-clamp-1">
                                        {{ $time->format('g:i A') }}
                                    </div>
                                    <div class="text-[10px] font-semibold text-purple-800 leading-tight line-clamp-1 mt-0.5">
                                        {{ $app->client->name ?? 'Desc.' }}
                                    </div>
                                    @if($visualDuration > 30)
                                    <div class="text-[8px] text-purple-600 line-clamp-1 mt-0.5">
                                        @foreach($app->services as $srv) {{ $srv->name }}, @endforeach
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    @else
        
        <!-- MINI-CARDS GRID (Mensual) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden mb-8">
            <!-- Days of Week Header -->
            <div class="border-b border-gray-100 bg-[#f8f5fa]" style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr));">
                @foreach(['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $dayName)
                    <div class="py-3 text-center text-[10px] font-bold uppercase tracking-wider text-gray-500">
                        {{ $dayName }}
                    </div>
                @endforeach
            </div>
            
            <!-- Calendar Days Grid -->
            <div style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr));">
                @foreach($monthlyData as $day)
                    @if($day['isPadding'])
                        <div class="bg-gray-50/50 border-b border-r border-gray-50" style="aspect-ratio: 1 / 1;"></div>
                    @else
                        @php 
                            $isSelected = $selectedDate === $day['date'];
                        @endphp
                        <div wire:click="selectDate('{{ $day['date'] }}')" 
                             class="p-2 border-b border-r border-gray-50 relative cursor-pointer transition flex flex-col overflow-hidden {{ $isSelected ? 'bg-purple-50/30 ring-inset ring-2 ring-[#c791e8]' : 'hover:bg-gray-50' }}"
                             style="aspect-ratio: 1 / 1;">
                            
                            <!-- Day Number -->
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-xs font-bold {{ $isSelected ? 'text-[#c791e8]' : ($day['isToday'] ? 'text-white bg-[#c791e8] rounded-full h-6 w-6 flex items-center justify-center' : 'text-gray-600') }}">
                                    {{ $day['dayNumber'] }}
                                </span>
                            </div>

                            <!-- Appointments Mini-Cards -->
                            <div class="flex flex-col gap-1.5 overflow-y-auto max-h-[120px] md:max-h-[160px] custom-scrollbar">
                                @foreach($day['appointments'] as $app)
                                    <div class="bg-[#F6EBFF] rounded text-[10px] px-2 py-1 text-purple-900 truncate border-l-[3px] border-[#c791e8]"
                                         title="{{ \Carbon\Carbon::parse($app->time)->format('g:i A') }} - {{ $app->client->name ?? 'Desc.' }}">
                                        <span class="font-bold">{{ \Carbon\Carbon::parse($app->time)->format('g:i') }}</span> 
                                        {{ $app->client->name ?? 'Desc.' }}
                                    </div>
                                @endforeach
                            </div>
                            
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    <!-- DETALLE DEL DÍA SELECCIONADO (Se muestra en ambas vistas abajo) -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden mt-6">
        <div class="bg-[#f8f5fa] px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-sm text-[#2c1a36] capitalize">
                Detalles del {{ \Carbon\Carbon::parse($selectedDate)->isoFormat('dddd, D [de] MMMM') }}
            </h3>
            <span class="text-xs font-bold text-gray-500 bg-gray-200 px-2 py-1 rounded-md">{{ $selectedAppointments->count() }} Citas</span>
        </div>

        @if($selectedAppointments->isEmpty())
            <div class="flex flex-col items-center justify-center py-10">
                <p class="text-xs text-gray-400 mt-1">Día sin programaciones.</p>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($selectedAppointments as $appointment)
                    @php
                        $formattedTime = \Carbon\Carbon::parse($appointment->time)->format('g:i A');
                    @endphp
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50/50">
                        <div class="flex items-center gap-4">
                            <span class="text-sm font-bold text-[#c791e8] w-20">{{ $formattedTime }}</span>
                            <div>
                                <h4 class="font-bold text-sm text-[#2c1a36]">{{ $appointment->client->name ?? 'Desconocido' }}</h4>
                                <div class="text-[10px] text-gray-500 mt-0.5">
                                    @foreach($appointment->services as $service) {{ $service->name }}, @endforeach
                                </div>
                            </div>
                        </div>
                        <span class="text-xs font-black text-gray-400">${{ number_format($appointment->services->sum('price'), 2) }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Scrollbar styling for mini-cards -->
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #e2d5ec;
            border-radius: 10px;
        }
    </style>
</div>
