<div class="text-[#2c1a36]">
    
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="font-bold text-2xl tracking-tight flex items-center gap-2">
            <i class="fa-solid fa-scissors text-[#c791e8]"></i>
            Mi Agenda del Día
        </h1>
        <p class="text-xs text-gray-500 mt-1">Hoy es {{ \Carbon\Carbon::parse($todayDate)->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
    </div>

    <!-- KPIs Section -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <!-- Total -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-xl">
                <i class="fa-solid fa-list-check"></i>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Total Citas</span>
                <span class="block text-xl font-bold text-[#2c1a36]">{{ $kpis['total'] }}</span>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-yellow-50 text-yellow-500 flex items-center justify-center text-xl">
                <i class="fa-regular fa-clock"></i>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Pendientes</span>
                <span class="block text-xl font-bold text-[#2c1a36]">{{ $kpis['pending'] }}</span>
            </div>
        </div>

        <!-- Completed -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-50 text-green-500 flex items-center justify-center text-xl">
                <i class="fa-solid fa-check-double"></i>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Completadas</span>
                <span class="block text-xl font-bold text-[#2c1a36]">{{ $kpis['completed'] }}</span>
            </div>
        </div>

        <!-- No Show -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-red-50 text-red-500 flex items-center justify-center text-xl">
                <i class="fa-solid fa-user-xmark"></i>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">No Asistió</span>
                <span class="block text-xl font-bold text-[#2c1a36]">{{ $kpis['no_show'] }}</span>
            </div>
        </div>
    </div>

    <!-- Timeline / Appointments List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden">
        <div class="bg-[#f8f5fa] px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-sm text-[#2c1a36]">Pacientes Programados para Hoy</h3>
        </div>

        @if($appointments->isEmpty())
            <div class="flex flex-col items-center justify-center py-16">
                <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-mug-hot text-2xl text-gray-300"></i>
                </div>
                <h3 class="font-bold text-lg text-gray-700">Día Libre</h3>
                <p class="text-xs text-gray-400 mt-1 max-w-sm text-center px-4">
                    No tienes ninguna cita asignada para el día de hoy. ¡Aprovecha para descansar o capacitarte!
                </p>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($appointments as $appointment)
                    @php
                        $isCompleted = in_array($appointment->status, ['completed', 'realizada']);
                        $isNoShow = $appointment->status === 'no_show';
                        $isPending = in_array($appointment->status, ['pending', 'confirmed', 'approved']);
                        $formattedTime = \Carbon\Carbon::parse($appointment->time)->format('g:i A');
                    @endphp
                    
                    <div class="p-6 hover:bg-gray-50/50 transition flex flex-col md:flex-row md:items-center gap-6 {{ $isCompleted || $isNoShow ? 'opacity-60 grayscale-[30%]' : '' }}">
                        
                        <!-- Time Column -->
                        <div class="flex-shrink-0 w-24 border-l-4 {{ $isCompleted ? 'border-green-400' : ($isNoShow ? 'border-red-400' : 'border-[#c791e8]') }} pl-4">
                            <span class="block text-lg font-bold text-[#2c1a36]">{{ $formattedTime }}</span>
                            @if($isCompleted)
                                <span class="text-[10px] font-bold text-green-500 uppercase tracking-wider">Finalizada</span>
                            @elseif($isNoShow)
                                <span class="text-[10px] font-bold text-red-500 uppercase tracking-wider">No Asistió</span>
                            @else
                                <span class="text-[10px] font-bold text-[#c791e8] uppercase tracking-wider">Pendiente</span>
                            @endif
                        </div>

                        <!-- Client Info -->
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="font-bold text-base text-[#2c1a36]">{{ $appointment->client->name ?? 'Cliente Eliminado' }}</h4>
                                <a href="mailto:{{ $appointment->client->email ?? '' }}" class="text-gray-400 hover:text-[#c791e8] transition">
                                    <i class="fa-regular fa-envelope text-xs"></i>
                                </a>
                            </div>
                            
                            <!-- Services -->
                            <div class="mt-2 space-y-1">
                                @foreach($appointment->services as $service)
                                    <div class="inline-flex items-center gap-1.5 px-2 py-1 bg-gray-100 rounded text-[10px] font-semibold text-gray-600 mr-2 mb-1">
                                        <i class="fa-solid fa-spa text-[#c791e8]"></i> {{ $service->name }} ({{ $service->duration }}m)
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex-shrink-0 flex items-center gap-2">
                            @if($isPending)
                                <button wire:click="markAsCompleted({{ $appointment->id }})" 
                                        class="px-4 py-2 bg-green-50 text-green-600 hover:bg-green-500 hover:text-white border border-green-200 transition-all text-xs font-bold rounded-lg shadow-sm flex items-center gap-2"
                                        title="Terminar servicio y emitir PDF al cliente">
                                    <i class="fa-solid fa-check"></i> Completar
                                </button>
                                
                                <button wire:click="markAsNoShow({{ $appointment->id }})" 
                                        class="px-3 py-2 bg-white text-gray-400 hover:bg-red-50 hover:text-red-600 border border-gray-200 hover:border-red-200 transition-all text-xs font-bold rounded-lg shadow-sm"
                                        title="Marcar como inasistencia">
                                    <i class="fa-solid fa-user-xmark"></i>
                                </button>
                            @else
                                <div class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg text-xs font-bold flex items-center gap-2 cursor-not-allowed border border-gray-200">
                                    <i class="fa-solid fa-lock text-[10px]"></i> Cita Cerrada
                                </div>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
