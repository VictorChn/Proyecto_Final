<div class="text-[#2c1a36]">
    @if($activeAppointments->isEmpty())
        <!-- Empty State -->
        <div class="flex flex-col items-center justify-center py-20 bg-[#f8f5fa] rounded-2xl border border-dashed border-purple-100">
            <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center shadow-sm mb-4">
                <i class="fa-regular fa-calendar-xmark text-2xl text-[#c791e8]"></i>
            </div>
            <h3 class="font-bold text-lg">No tienes citas próximas</h3>
            <p class="text-xs text-gray-400 mt-1 max-w-sm text-center px-4">
                No tienes ninguna reservación activa programada por el momento. ¡Reserva un servicio de belleza hoy mismo!
            </p>
            <a href="{{ route('seleccionar-servicios') }}" class="mt-6 px-5 py-2 border border-[#c791e8] text-[#c791e8] font-bold text-xs rounded-lg hover:bg-[#f8f5fa] transition">
                Explorar Servicios
            </a>
        </div>
    @else
        <!-- Active Appointments Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($activeAppointments as $appointment)
                @php
                    $totalPrice = $appointment->services->sum('price');
                    $totalDuration = $appointment->services->sum('duration');
                    $formattedDate = Carbon\Carbon::parse($appointment->scheduled_date)->isoFormat('dddd, D [de] MMMM');
                    $formattedTime = Carbon\Carbon::parse($appointment->time)->format('g:i A');
                    
                    // Format total duration
                    if ($totalDuration < 60) {
                        $durationText = $totalDuration . ' min';
                    } else {
                        $hours = floor($totalDuration / 60);
                        $mins = $totalDuration % 60;
                        $durationText = $hours . 'h' . ($mins > 0 ? ' ' . $mins . 'm' : '');
                    }

                    // Check if it's less than 24 hours away
                    $appointmentDateTime = Carbon\Carbon::parse($appointment->scheduled_date . ' ' . $appointment->time);
                    $isLocked = Carbon\Carbon::now()->diffInHours($appointmentDateTime, false) < 24;
                @endphp
                <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-all duration-200 flex flex-col justify-between">
                    
                    <!-- Card Header -->
                    <div class="bg-[#f8f5fa] p-4 border-b border-gray-50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fa-regular fa-calendar text-xs text-[#c791e8]"></i>
                            <span class="text-xs font-bold capitalize">{{ $formattedDate }}</span>
                        </div>
                        
                        <!-- Status Badges -->
                        @if($appointment->status === 'pending')
                            <span class="px-2.5 py-0.5 bg-yellow-50 text-yellow-700 text-[10px] font-bold rounded-full uppercase tracking-wider">
                                Pendiente
                            </span>
                        @elseif($appointment->status === 'approved' || $appointment->status === 'confirmed')
                            <span class="px-2.5 py-0.5 bg-green-50 text-green-700 text-[10px] font-bold rounded-full uppercase tracking-wider">
                                Confirmada
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 bg-purple-50 text-purple-700 text-[10px] font-bold rounded-full uppercase tracking-wider">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        @endif
                    </div>

                    <!-- Card Body -->
                    <div class="p-4 space-y-4">
                        <!-- Time and Stylist info -->
                        <div class="flex items-center justify-between border-b border-gray-50 pb-3">
                            <div class="flex items-center gap-2">
                                <i class="fa-regular fa-clock text-xs text-[#c791e8]"></i>
                                <span class="text-sm font-bold">{{ $formattedTime }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-right">
                                <span class="text-[10px] text-gray-400 block font-semibold">ESTILISTA</span>
                                <span class="text-xs font-bold text-gray-700">{{ $appointment->specialist->user->name }}</span>
                            </div>
                        </div>

                        <!-- Services List -->
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block mb-2">Servicios</span>
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
                    <div class="bg-gray-50/50 p-4 border-t border-gray-50 flex items-center justify-between text-xs">
                        <div class="text-gray-400">
                            Duración: <span class="font-bold text-gray-600">{{ $durationText }}</span>
                        </div>
                        <div class="font-bold text-[#c791e8] text-sm">
                            Total: ${{ number_format($totalPrice, 2) }}
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div class="p-4 bg-white border-t border-gray-50 flex gap-2">
                        @if($isLocked)
                            <button disabled class="flex-1 py-2 bg-gray-100 text-gray-450 text-xs font-bold rounded-lg cursor-not-allowed flex items-center justify-center gap-1.5 opacity-60" title="No modificable a menos de 24 horas">
                                <i class="fa-solid fa-lock text-[10px] text-gray-400"></i> Reagendar
                            </button>
                            <button disabled class="flex-1 py-2 bg-gray-100 text-gray-450 text-xs font-bold rounded-lg cursor-not-allowed flex items-center justify-center gap-1.5 opacity-60" title="No modificable a menos de 24 horas">
                                <i class="fa-solid fa-lock text-[10px] text-gray-400"></i> Cancelar
                            </button>
                        @else
                            <button wire:click="startReschedule({{ $appointment->id }})" class="flex-1 py-2 border border-[#c791e8] text-[#c791e8] text-xs font-semibold rounded-lg hover:bg-[#f8f5fa] transition flex items-center justify-center gap-1.5">
                                <i class="fa-regular fa-clock text-[10px]"></i> Reagendar
                            </button>
                            <button wire:click="confirmCancel({{ $appointment->id }})" class="flex-1 py-2 bg-red-50 text-red-600 hover:bg-red-100 text-xs font-semibold rounded-lg transition flex items-center justify-center gap-1.5">
                                <i class="fa-regular fa-trash-can text-[10px]"></i> Cancelar
                            </button>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>
    @endif

    <!-- Reschedule Modal -->
    @if($isRescheduling)
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-purple-100 overflow-hidden transform transition-all">
                
                <!-- Modal Header -->
                <div class="bg-[#c791e8] px-6 py-4 text-white flex items-center justify-between">
                    <h3 class="font-bold text-lg flex items-center gap-2">
                        <i class="fa-regular fa-calendar-days"></i> Reagendar tu Cita
                    </h3>
                    <button wire:click="closeReschedule" class="text-white/80 hover:text-white transition">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">1. Selecciona Nueva Fecha</label>
                        <input wire:model.live="selectedDate" type="date" min="{{ date('Y-m-d') }}" 
                               class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-[#c791e8] focus:border-[#c791e8] text-sm text-[#2c1a36] font-semibold">
                        
                        <div class="mt-2 text-[10px] text-gray-400">
                            La estética labora de lunes a viernes de 8:00 AM a 5:00 PM.
                        </div>

                        @error('selectedDate')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">2. Horarios Disponibles</label>
                        
                        @if(Carbon\Carbon::parse($selectedDate)->isWeekend())
                            <div class="p-4 text-center bg-red-50 rounded-xl border border-red-100 text-red-600">
                                <i class="fa-regular fa-circle-xmark text-xl mb-1 block"></i>
                                <p class="text-xs font-bold">Estética Cerrada</p>
                                <p class="text-[10px]">Por favor elige un día de lunes a viernes.</p>
                            </div>
                        @elseif(empty($this->timeSlots))
                            <div class="p-4 text-center bg-gray-50 rounded-xl border border-gray-100 text-gray-400">
                                <i class="fa-regular fa-clock text-xl mb-1 block text-[#c791e8]"></i>
                                <p class="text-xs font-semibold">Cargando horarios...</p>
                            </div>
                        @else
                            <div class="grid grid-cols-3 gap-2 max-h-48 overflow-y-auto pr-1">
                                @foreach($this->timeSlots as $slot)
                                    @php 
                                        $slotTime = $slot['time'];
                                        $isAvailable = $slot['available'];
                                        $formattedSlot = Carbon\Carbon::parse($slotTime)->format('g:i A');
                                        $isTimeSelected = $selectedTime === $slotTime;
                                    @endphp
                                    @if($isAvailable)
                                        <button wire:click="$set('selectedTime', '{{ $slotTime }}')" 
                                                class="py-2 rounded-lg text-xs font-bold text-center border transition {{ $isTimeSelected ? 'bg-[#c791e8] text-white border-[#c791e8] shadow-md shadow-purple-50' : 'bg-white hover:bg-gray-50 text-[#2c1a36] border-gray-200' }}">
                                            {{ $formattedSlot }}
                                        </button>
                                    @else
                                        <button disabled 
                                                class="py-2 rounded-lg text-xs font-bold text-center border bg-gray-100 text-gray-400 border-gray-100 cursor-not-allowed opacity-50">
                                            {{ $formattedSlot }}
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        @error('selectedTime')
                            <span class="text-xs text-red-500 mt-2 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <button wire:click="closeReschedule" class="px-4 py-2 border border-gray-300 text-gray-600 font-semibold rounded-lg hover:bg-gray-150 transition text-xs">
                        Cancelar
                    </button>
                    
                    <button wire:click="saveReschedule" class="px-5 py-2 bg-[#c791e8] text-white font-semibold rounded-lg hover:bg-[#a66cc9] transition shadow-md shadow-purple-100 text-xs flex items-center gap-1">
                        Confirmar Cambios <i class="fa-solid fa-check text-[10px]"></i>
                    </button>
                </div>

            </div>
        </div>
    @endif
</div>
