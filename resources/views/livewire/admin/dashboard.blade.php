<div class="text-[#2c1a36]" wire:poll.30s>

    <!-- ═══ HEADER ═══ -->
    <div class="mb-8 px-5 lg:px-0">
        <h1 class="font-bold text-2xl tracking-tight">Panel de Administración</h1>
        <p class="text-xs text-gray-400 mt-1">Resumen general del negocio — {{ \Carbon\Carbon::now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
    </div>

    <!-- ═══ KPIs DEL DÍA ═══ -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">

        <!-- Ganancias del Día -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-green-50 rounded-full -mr-8 -mt-8"></div>
            <div class="relative">
                <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-dollar-sign text-green-600"></i>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Ganancias Hoy</p>
                <h3 class="text-2xl font-black text-[#2c1a36]">${{ number_format($this->todayEarnings, 2) }}</h3>
                <p class="text-[10px] text-gray-400 mt-2">Servicios completados</p>
            </div>
        </div>

        <!-- Citas Hoy -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-purple-50 rounded-full -mr-8 -mt-8"></div>
            <div class="relative">
                <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center mb-4">
                    <i class="fa-regular fa-calendar-check text-[#c791e8]"></i>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Citas Hoy</p>
                <h3 class="text-2xl font-black text-[#2c1a36]">{{ $this->todayAppointments }}</h3>
                <p class="text-[10px] text-gray-400 mt-2">{{ $this->todayCompleted }} completadas</p>
            </div>
        </div>

        <!-- No-Shows -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-red-50 rounded-full -mr-8 -mt-8"></div>
            <div class="relative">
                <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-user-xmark text-red-500"></i>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">No-Shows Hoy</p>
                <h3 class="text-2xl font-black text-[#2c1a36]">{{ $this->todayNoShows }}</h3>
                <p class="text-[10px] text-gray-400 mt-2">Inasistencias</p>
            </div>
        </div>

        <!-- Clientes Nuevos -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-full -mr-8 -mt-8"></div>
            <div class="relative">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-user-plus text-blue-500"></i>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Clientes Nuevos</p>
                <h3 class="text-2xl font-black text-[#2c1a36]">{{ $this->newClientsWeek }}</h3>
                <p class="text-[10px] text-gray-400 mt-2">Esta semana</p>
            </div>
        </div>
    </div>

    <!-- ═══ FINANCIERO + TOP SERVICIOS ═══ -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-8">

        <!-- Resumen Financiero -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-sm mb-6 flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-[#c791e8]"></i> Resumen Financiero
            </h2>

            <div class="space-y-5">
                <!-- Ganancias Semanales -->
                <div class="flex items-center justify-between p-4 bg-[#f8f5fa] rounded-xl">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Esta Semana</p>
                        <h4 class="text-xl font-black text-[#2c1a36] mt-1">${{ number_format($this->weekEarnings, 2) }}</h4>
                    </div>
                    <div class="text-right">
                        @php $growth = $this->weekGrowth; @endphp
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold {{ $growth >= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            <i class="fa-solid {{ $growth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} text-[8px]"></i>
                            {{ abs($growth) }}%
                        </span>
                        <p class="text-[9px] text-gray-400 mt-1">vs semana pasada</p>
                    </div>
                </div>

                <!-- Ganancias Mensuales -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Este Mes</p>
                        <h4 class="text-xl font-black text-[#2c1a36] mt-1">${{ number_format($this->monthEarnings, 2) }}</h4>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-gray-400 capitalize">{{ \Carbon\Carbon::now()->isoFormat('MMMM YYYY') }}</span>
                    </div>
                </div>

                <!-- Semana Pasada (referencia) -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Semana Pasada</p>
                        <h4 class="text-xl font-black text-gray-400 mt-1">${{ number_format($this->lastWeekEarnings, 2) }}</h4>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-gray-400">Referencia</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top 5 Servicios -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-sm mb-6 flex items-center gap-2">
                <i class="fa-solid fa-ranking-star text-[#c791e8]"></i> Servicios Más Solicitados
            </h2>
            <p class="text-[10px] text-gray-400 -mt-4 mb-5">Ranking del mes actual</p>

            <div class="space-y-4">
                @forelse($this->topServices as $index => $service)
                    @php
                        $maxCount = $this->topServices->max('appointments_count') ?: 1;
                        $percentage = ($service->appointments_count / $maxCount) * 100;
                        $medals = ['🥇', '🥈', '🥉'];
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <span class="text-sm">{{ $medals[$index] ?? ($index + 1) . '.' }}</span>
                                <span class="text-xs font-bold text-[#2c1a36]">{{ $service->name }}</span>
                            </div>
                            <span class="text-[10px] font-bold text-gray-400">{{ $service->appointments_count }} reservas</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="h-2 rounded-full bg-gradient-to-r from-[#c791e8] to-[#a855f7] transition-all duration-500" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center py-8">No hay datos este mes.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ═══ TIMELINE DEL DÍA + ACTIVIDAD RECIENTE ═══ -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6">

        <!-- Citas de Hoy (Timeline) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden lg:col-span-7">
            <div class="bg-[#f8f5fa] px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-sm flex items-center gap-2">
                    <i class="fa-solid fa-clock text-[#c791e8]"></i> Agenda de Hoy
                </h2>
                <span class="text-[10px] font-bold text-gray-500 bg-white px-2.5 py-1 rounded-md shadow-sm">{{ $this->todayTimeline->count() }} Citas</span>
            </div>

            @if($this->todayTimeline->isEmpty())
                <div class="flex flex-col items-center justify-center py-14">
                    <div class="w-14 h-14 rounded-full bg-gray-50 flex items-center justify-center mb-3">
                        <i class="fa-regular fa-face-smile text-2xl text-gray-300"></i>
                    </div>
                    <p class="text-xs text-gray-400">No hay citas programadas para hoy.</p>
                </div>
            @else
                <div class="divide-y divide-gray-50">
                    @foreach($this->todayTimeline as $appointment)
                        @php
                            $formattedTime = \Carbon\Carbon::parse($appointment->time)->format('g:i A');
                            $isCompleted = in_array($appointment->status, ['completed', 'realizada']);
                            $isNoShow = $appointment->status === 'no_show';
                            $isPending = !$isCompleted && !$isNoShow;
                        @endphp
                        <div class="px-6 py-4 flex items-center gap-4 {{ $isCompleted || $isNoShow ? 'opacity-50' : '' }}">
                            <!-- Time -->
                            <div class="flex-shrink-0 w-16 text-center">
                                <span class="text-xs font-black text-[#c791e8]">{{ $formattedTime }}</span>
                            </div>
                            <!-- Status dot -->
                            <div class="flex-shrink-0">
                                <div class="w-2.5 h-2.5 rounded-full {{ $isCompleted ? 'bg-green-400' : ($isNoShow ? 'bg-red-400' : 'bg-[#c791e8] animate-pulse') }}"></div>
                            </div>
                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-[#2c1a36] truncate">{{ $appointment->client->name ?? 'Desconocido' }}</p>
                                <p class="text-[10px] text-gray-400 truncate">
                                    <i class="fa-solid fa-user-tie text-[8px]"></i> {{ $appointment->specialist->user->name ?? 'Sin asignar' }}
                                    · @foreach($appointment->services as $s) {{ $s->name }} @endforeach
                                </p>
                            </div>
                            <!-- Price -->
                            <div class="flex-shrink-0">
                                <span class="text-xs font-black text-gray-400">${{ number_format($appointment->services->sum('price'), 2) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Actividad Reciente -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden lg:col-span-5">
            <div class="bg-[#f8f5fa] px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-sm flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-[#c791e8]"></i> Actividad Reciente
                </h2>
            </div>

            @if($this->recentAppointments->isEmpty())
                <div class="flex flex-col items-center justify-center py-14">
                    <p class="text-xs text-gray-400">Sin actividad registrada.</p>
                </div>
            @else
                <div class="divide-y divide-gray-50">
                    @foreach($this->recentAppointments as $recent)
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'confirmed' => 'bg-blue-100 text-blue-700',
                                'approved' => 'bg-blue-100 text-blue-700',
                                'completed' => 'bg-green-100 text-green-700',
                                'realizada' => 'bg-green-100 text-green-700',
                                'cancelled' => 'bg-gray-100 text-gray-500',
                                'cancelada' => 'bg-gray-100 text-gray-500',
                                'no_show' => 'bg-red-100 text-red-700',
                            ];
                            $statusLabels = [
                                'pending' => 'Pendiente',
                                'confirmed' => 'Confirmada',
                                'approved' => 'Aprobada',
                                'completed' => 'Completada',
                                'realizada' => 'Realizada',
                                'cancelled' => 'Cancelada',
                                'cancelada' => 'Cancelada',
                                'no_show' => 'No Asistió',
                            ];
                            $color = $statusColors[$recent->status] ?? 'bg-gray-100 text-gray-500';
                            $label = $statusLabels[$recent->status] ?? $recent->status;
                        @endphp
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-xs font-bold text-[#2c1a36] truncate">{{ $recent->client->name ?? 'Desconocido' }}</p>
                                <span class="text-[9px] font-bold px-2 py-0.5 rounded-full {{ $color }}">{{ $label }}</span>
                            </div>
                            <p class="text-[10px] text-gray-400">
                                {{ \Carbon\Carbon::parse($recent->scheduled_date)->isoFormat('D MMM') }} 
                                · {{ \Carbon\Carbon::parse($recent->time)->format('g:i A') }}
                                · ${{ number_format($recent->services->sum('price'), 2) }}
                            </p>
                            <p class="text-[10px] text-gray-300 mt-0.5">Agendada {{ $recent->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
