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
                            @if($appointment->payment_status === 'pending')
                                <span class="px-2.5 py-0.5 bg-red-50 text-red-700 text-[10px] font-bold rounded-full uppercase tracking-wider animate-pulse">
                                    Pendiente Pago
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 bg-yellow-50 text-yellow-700 text-[10px] font-bold rounded-full uppercase tracking-wider">
                                    Pendiente
                                </span>
                            @endif
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
                        @if($appointment->payment_status === 'pending')
                            <a href="{{ route('seleccionar-servicios', ['appointment_id' => $appointment->id]) }}" class="flex-1 py-2 bg-[#c791e8] text-white hover:bg-[#a66cc9] text-xs font-bold rounded-lg transition flex items-center justify-center gap-1.5 shadow-sm shadow-purple-50">
                                <i class="fa-solid fa-credit-card text-[10px]"></i> Pagar Anticipo
                            </a>
                            <button wire:click="confirmCancel({{ $appointment->id }})" class="px-3 py-2 bg-red-50 text-red-600 hover:bg-red-100 text-xs font-semibold rounded-lg transition flex items-center justify-center gap-1.5" title="Cancelar cita no pagada">
                                <i class="fa-regular fa-trash-can text-[10px]"></i>
                            </button>
                        @elseif($isLocked)
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

    <!-- Stripe Payment Success / Processing Modal -->
    @if($showPaymentModal)
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/60 backdrop-blur-md animate-fade-in" 
             @if($paymentStatus === 'processing') wire:poll.1500ms="checkPaymentStatus" @endif>
            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-purple-100 overflow-hidden transform transition-all p-6 text-center space-y-6">
                
                @if($paymentStatus === 'processing')
                    <!-- PROCESSING STATE -->
                    <div class="py-8 space-y-4">
                        <div class="flex items-center justify-center">
                            <div class="relative w-20 h-20">
                                <!-- Outer glowing ring -->
                                <div class="absolute inset-0 rounded-full border-4 border-purple-100 animate-pulse"></div>
                                <!-- Inner spinning loader -->
                                <div class="absolute inset-0 rounded-full border-4 border-t-[#c791e8] border-r-transparent border-b-transparent border-l-transparent animate-spin"></div>
                                <!-- Center card icon -->
                                <div class="absolute inset-0 flex items-center justify-center text-[#c791e8] text-2xl">
                                    <i class="fa-solid fa-credit-card animate-bounce"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <h3 class="font-bold text-xl text-[#2c1a36]">Confirmando tu Pago de Anticipo</h3>
                            <p class="text-xs text-gray-500 max-w-sm mx-auto">
                                Estamos verificando la transacción con Stripe de manera segura. Tu cita se registrará automáticamente en unos segundos.
                            </p>
                        </div>

                        <!-- Progress indicator / logs -->
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-purple-50 text-[#c791e8] rounded-full text-[10px] font-semibold tracking-wide uppercase animate-pulse">
                            <i class="fa-solid fa-circle-nodes"></i> Validando Pago (Intento {{ $pollAttempts }} de 15)
                        </div>
                    </div>

                @elseif($paymentStatus === 'succeeded')
                    <!-- SUCCESS STATE -->
                    <div class="py-4 space-y-5">
                        <!-- Success Checkmark Animation -->
                        <div class="flex items-center justify-center">
                            <div class="w-16 h-16 rounded-full bg-emerald-50 border-2 border-emerald-500 flex items-center justify-center text-emerald-500 text-3xl shadow-md shadow-emerald-50">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <h3 class="font-bold text-2xl text-[#2c1a36]">¡Pago Completado!</h3>
                            <p class="text-xs text-gray-400">Tu cita en Gio Salon & Angie Nails ha sido confirmada y registrada en nuestro calendario.</p>
                        </div>

                        @if($paidAppointment)
                            <!-- Cita Info Box -->
                            <div class="bg-[#f8f5fa] border border-purple-50/50 rounded-2xl p-4 text-left space-y-3">
                                <div class="flex justify-between items-center border-b border-purple-100/50 pb-2">
                                    <span class="text-[10px] font-bold text-purple-400 uppercase">Detalles de Reservación</span>
                                    <span class="px-2 py-0.5 bg-green-50 text-green-700 text-[10px] font-bold rounded-full uppercase">Confirmada & Pagada</span>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 text-xs">
                                    <div>
                                        <span class="text-gray-400 block">Fecha y Hora</span>
                                        <span class="font-bold text-[#2c1a36]">
                                            {{ Carbon\Carbon::parse($paidAppointment->scheduled_date)->isoFormat('dddd, D [de] MMMM') }}<br>
                                            <span class="text-[#c791e8] font-bold text-sm">{{ Carbon\Carbon::parse($paidAppointment->time)->format('g:i A') }}</span>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-gray-400 block">Estilista</span>
                                        <span class="font-bold text-[#2c1a36]">{{ $paidAppointment->specialist->user->name }}</span>
                                    </div>
                                </div>

                                <div class="border-t border-purple-100/50 pt-2 space-y-1">
                                    <span class="text-gray-400 text-[10px] block font-bold uppercase">Servicios</span>
                                    <ul class="space-y-1 text-xs font-semibold text-gray-700 max-h-24 overflow-y-auto">
                                        @foreach($paidAppointment->services as $service)
                                            <li class="flex justify-between">
                                                <span>• {{ $service->name }}</span>
                                                <span class="text-gray-400">${{ number_format($service->price, 2) }} MXN</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="border-t border-dashed border-purple-200 pt-2 flex justify-between text-xs font-bold text-[#2c1a36]">
                                    <span>Monto Total</span>
                                    <span>${{ number_format($paidAppointment->services->sum('price'), 2) }} MXN</span>
                                </div>
                                <div class="flex justify-between text-xs font-bold text-[#c791e8]">
                                    <span>Anticipo Abonado (50%)</span>
                                    <span>${{ number_format($paidAppointment->amount_paid, 2) }} MXN</span>
                                </div>
                            </div>
                        @endif

                        <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl flex gap-3 text-xs text-amber-800 text-left">
                            <div class="text-lg text-amber-500 mt-0.5">
                                <i class="fa-solid fa-envelope-open-text animate-pulse"></i>
                            </div>
                            <div>
                                <span class="font-bold block text-[#2c1a36]">¡Correo enviado!</span>
                                Hemos enviado la confirmación detallada con las políticas de servicio a tu correo registrado.
                            </div>
                        </div>

                        <div class="pt-2">
                            <button wire:click="closePaymentModal" class="w-full py-3 bg-[#c791e8] text-white hover:bg-[#a66cc9] transition font-bold rounded-xl text-xs flex items-center justify-center gap-2 shadow-lg shadow-purple-100">
                                Entendido, ver mis citas <i class="fa-solid fa-circle-check"></i>
                            </button>
                        </div>
                    </div>

                @elseif($paymentStatus === 'timeout')
                    <!-- TIMEOUT STATE -->
                    <div class="py-6 space-y-4">
                        <div class="flex items-center justify-center">
                            <div class="w-16 h-16 rounded-full bg-amber-50 border-2 border-amber-500 flex items-center justify-center text-amber-500 text-3xl">
                                <i class="fa-solid fa-circle-exclamation"></i>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <h3 class="font-bold text-xl text-[#2c1a36]">La confirmación está tardando</h3>
                            <p class="text-xs text-gray-500 max-w-sm mx-auto">
                                Stripe procesó tu pago correctamente, pero el servidor está tomando más tiempo en registrar el estado. Tu espacio está seguro.
                            </p>
                        </div>
                        <div class="pt-4 flex gap-3">
                            <button wire:click="checkPaymentStatus" class="flex-1 py-2.5 border border-[#c791e8] text-[#c791e8] hover:bg-purple-50 transition font-semibold rounded-lg text-xs">
                                <i class="fa-solid fa-arrows-rotate mr-1"></i> Verificar
                            </button>
                            <button wire:click="closePaymentModal" class="flex-1 py-2.5 bg-[#c791e8] text-white hover:bg-[#a66cc9] transition font-bold rounded-lg text-xs">
                                Ir al Dashboard
                            </button>
                        </div>
                    </div>

                @else
                    <!-- FAILED STATE -->
                    <div class="py-6 space-y-4">
                        <div class="flex items-center justify-center">
                            <div class="w-16 h-16 rounded-full bg-red-50 border-2 border-red-500 flex items-center justify-center text-red-500 text-3xl">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <h3 class="font-bold text-xl text-[#2c1a36]">Error al Verificar el Pago</h3>
                            <p class="text-xs text-gray-500 max-w-sm mx-auto">
                                No logramos confirmar el pago de tu anticipo. Si tu banco realizó el cargo, por favor comunícate a soporte técnico.
                            </p>
                        </div>
                        <div class="pt-4">
                            <button wire:click="closePaymentModal" class="w-full py-2.5 bg-red-600 text-white hover:bg-red-700 transition font-bold rounded-lg text-xs">
                                Cerrar Ventana
                            </button>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    @endif
</div>
