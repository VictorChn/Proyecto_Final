<div class="text-[#2c1a36]">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-purple-50/50">
        
        <!-- Header Title -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="font-bold text-2xl tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-[#c791e8]"></i>
                    Historial de Desempeño
                </h1>
                <p class="text-xs text-gray-500 mt-1">Auditoría de todos los tratamientos completados y clientes que no asistieron.</p>
            </div>
            <div class="px-4 py-2 bg-[#f8f5fa] rounded-lg border border-purple-100 flex items-center gap-3">
                <i class="fa-solid fa-chart-line text-[#c791e8]"></i>
                <div class="flex flex-col">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Citas Registradas</span>
                    <span class="text-lg font-black text-[#2c1a36] leading-none">{{ $appointments->count() }}</span>
                </div>
            </div>
        </div>

        @if($appointments->isEmpty())
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-20 bg-[#f8f5fa] rounded-2xl border border-dashed border-purple-100">
                <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center shadow-sm mb-4">
                    <i class="fa-solid fa-folder-open text-2xl text-[#c791e8]"></i>
                </div>
                <h3 class="font-bold text-lg">Historial en blanco</h3>
                <p class="text-xs text-gray-400 mt-1 max-w-sm text-center px-4">
                    Tus citas finalizadas o marcadas como inasistencia aparecerán aquí de forma permanente.
                </p>
            </div>
        @else
            <!-- Completed Appointments Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($appointments as $appointment)
                    @php
                        $totalPrice = $appointment->services->sum('price');
                        $totalDuration = $appointment->services->sum('duration');
                        $formattedDate = Carbon\Carbon::parse($appointment->scheduled_date)->isoFormat('dddd, D [de] MMMM [de] YYYY');
                        $formattedTime = Carbon\Carbon::parse($appointment->time)->format('g:i A');
                        $isNoShow = $appointment->status === 'no_show';
                        
                        // Format total duration
                        if ($totalDuration < 60) {
                            $durationText = $totalDuration . ' min';
                        } else {
                            $hours = floor($totalDuration / 60);
                            $mins = $totalDuration % 60;
                            $durationText = $hours . 'h' . ($mins > 0 ? ' ' . $mins . 'm' : '');
                        }
                    @endphp
                    
                    <div class="bg-white border border-gray-150 rounded-xl shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition-all duration-200 {{ $isNoShow ? 'opacity-80' : '' }}">
                        
                        <!-- Card Header -->
                        <div class="bg-[#fcfbfe] p-4 border-b border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="fa-regular fa-calendar text-xs {{ $isNoShow ? 'text-red-400' : 'text-[#c791e8]' }}"></i>
                                <span class="text-xs font-bold capitalize text-gray-700">{{ $formattedDate }}</span>
                            </div>
                            @if($isNoShow)
                                <span class="px-2.5 py-0.5 bg-red-50 text-red-700 text-[10px] font-bold rounded-full uppercase tracking-wider">
                                    No Asistió
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 bg-green-100 text-green-800 text-[10px] font-bold rounded-full uppercase tracking-wider">
                                    Completada
                                </span>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="p-4 space-y-4">
                            <!-- Time and Client info -->
                            <div class="flex items-center justify-between border-b border-gray-50 pb-3">
                                <div class="flex items-center gap-2">
                                    <i class="fa-regular fa-clock text-xs {{ $isNoShow ? 'text-red-400' : 'text-[#c791e8]' }}"></i>
                                    <span class="text-xs font-bold text-gray-700">{{ $formattedTime }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-right">
                                    <span class="text-[9px] text-gray-400 block font-semibold">PACIENTE</span>
                                    <span class="text-xs font-bold text-gray-600 line-clamp-1 max-w-[120px]" title="{{ $appointment->client->name ?? 'Desconocido' }}">
                                        {{ $appointment->client->name ?? 'Desconocido' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Services List -->
                            <div>
                                <span class="text-[9px] font-bold uppercase tracking-wider text-gray-400 block mb-2">Servicios Realizados</span>
                                <div class="space-y-1.5 max-h-24 overflow-y-auto pr-1">
                                    @foreach($appointment->services as $service)
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="font-medium text-gray-600 line-clamp-1 pr-3">{{ $service->name }}</span>
                                            <span class="font-semibold text-gray-400">${{ number_format($service->price, 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="bg-gray-50/50 p-4 border-t border-gray-100 flex items-center justify-between text-xs">
                            <div class="text-gray-400">
                                Trabajo total: <span class="font-bold text-gray-600">{{ $durationText }}</span>
                            </div>
                            <div class="font-bold text-gray-700 text-sm">
                                @if($isNoShow)
                                    Pérdida: <span class="text-red-500 line-through">${{ number_format($totalPrice, 2) }}</span>
                                @else
                                    Ingreso: <span class="text-[#c791e8]">${{ number_format($totalPrice, 2) }}</span>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
