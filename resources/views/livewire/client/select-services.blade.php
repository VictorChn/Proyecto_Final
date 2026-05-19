<div class="text-[#2c1a36]">
    <!-- Stepper Progress Bar -->
    <div class="mb-8">
        <div class="flex items-center justify-between max-w-xl mx-auto">
            <!-- Step 1 -->
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300 {{ $step >= 1 ? 'bg-[#c791e8] text-white shadow-lg' : 'bg-gray-200 text-gray-500' }}">
                    1
                </div>
                <span class="text-xs font-semibold mt-2 {{ $step >= 1 ? 'text-[#2c1a36]' : 'text-gray-400' }}">Servicios</span>
            </div>

            <div class="flex-1 h-1 mx-2 transition-all duration-300 {{ $step >= 2 ? 'bg-[#c791e8]' : 'bg-gray-200' }}"></div>

            <!-- Step 2 -->
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300 {{ $step >= 2 ? 'bg-[#c791e8] text-white shadow-lg' : 'bg-gray-200 text-gray-500' }}">
                    2
                </div>
                <span class="text-xs font-semibold mt-2 {{ $step >= 2 ? 'text-[#2c1a36]' : 'text-gray-400' }}">Estilista y Horario</span>
            </div>

            <div class="flex-1 h-1 mx-2 transition-all duration-300 {{ $step >= 3 ? 'bg-[#c791e8]' : 'bg-gray-200' }}"></div>

            <!-- Step 3 -->
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300 {{ $step >= 3 ? 'bg-[#c791e8] text-white shadow-lg' : 'bg-gray-200 text-gray-500' }}">
                    3
                </div>
                <span class="text-xs font-semibold mt-2 {{ $step >= 3 ? 'text-[#2c1a36]' : 'text-gray-400' }}">Confirmación</span>
            </div>
        </div>
    </div>

    <!-- Step 1: Services Catalog & Cart -->
    @if($step === 1)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side: Catalog -->
            <div class="lg:col-span-2">
                <!-- Search & Filters -->
                <div class="bg-white p-4 rounded-xl shadow-sm mb-6 flex flex-col md:flex-row md:items-center gap-4">
                    <!-- Search Input -->
                    <div class="flex-1 relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar servicios..." 
                               class="w-full pl-10 pr-4 py-2 bg-gray-50 border-gray-200 rounded-lg focus:ring-[#c791e8] focus:border-[#c791e8] text-sm text-[#2c1a36]">
                    </div>

                    <!-- Category Filter Tabs -->
                    <div class="flex flex-wrap gap-2">
                        <button wire:click="$set('selectedCategory', '')" 
                                class="px-4 py-1.5 rounded-lg text-xs font-semibold transition {{ !$selectedCategory ? 'bg-[#c791e8] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            Todos
                        </button>
                        @foreach($categories as $cat)
                            <button wire:click="$set('selectedCategory', '{{ $cat }}')" 
                                    class="px-4 py-1.5 rounded-lg text-xs font-semibold transition {{ $selectedCategory === $cat ? 'bg-[#c791e8] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                {{ $cat }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Services Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($services as $service)
                        @php $isSelected = in_array($service->id, $cart); @endphp
                        <div class="bg-white border rounded-xl p-5 transition-all duration-300 hover:shadow-md flex flex-col justify-between {{ $isSelected ? 'border-[#c791e8] ring-1 ring-[#c791e8]' : 'border-gray-100' }}">
                            <div>
                                <!-- Badge category -->
                                @if($service->category)
                                    <span class="inline-block px-2.5 py-0.5 bg-[#f8f5fa] text-[#c791e8] text-[10px] font-bold rounded-full uppercase tracking-wider mb-2">
                                        {{ $service->category }}
                                    </span>
                                @endif

                                <h3 class="font-bold text-base text-[#2c1a36] mb-1">{{ $service->name }}</h3>
                                <p class="text-xs text-gray-500 line-clamp-2 mb-4">{{ $service->description }}</p>
                            </div>

                            <div class="flex items-center justify-between border-t border-gray-50 pt-4 mt-2">
                                <!-- Price & Duration -->
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-400 flex items-center gap-1.5">
                                        <i class="fa-regular fa-clock"></i> {{ $service->duration }} min
                                    </span>
                                    <span class="text-base font-bold text-[#c791e8] mt-0.5">
                                        ${{ number_format($service->price, 2) }}
                                    </span>
                                </div>

                                <!-- Action Button -->
                                <button wire:click="toggleService({{ $service->id }})" 
                                        class="px-4 py-2 rounded-lg text-xs font-semibold transition-all duration-200 flex items-center gap-2 {{ $isSelected ? 'bg-green-550 bg-[#c791e8] text-white' : 'border border-[#c791e8] text-[#c791e8] hover:bg-[#f8f5fa]' }}">
                                    @if($isSelected)
                                        <i class="fa-solid fa-check"></i> Seleccionado
                                    @else
                                        <i class="fa-solid fa-plus"></i> Seleccionar
                                    @endif
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full bg-white rounded-xl p-8 text-center text-gray-500 shadow-sm border border-gray-100">
                            <i class="fa-solid fa-face-meh text-3xl mb-2 text-[#c791e8]"></i>
                            <p class="text-sm font-semibold">No se encontraron servicios disponibles.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Side: Sticky Shopping Cart -->
            <div class="lg:col-span-1">
                <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm sticky top-6">
                    <h3 class="font-bold text-lg text-[#2c1a36] border-b border-gray-100 pb-4 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-bag-shopping text-[#c791e8]"></i>
                        Resumen de Cita
                    </h3>

                    @if(empty($cart))
                        <div class="py-12 text-center text-gray-400">
                            <i class="fa-solid fa-cart-shopping text-3xl mb-3 opacity-30"></i>
                            <p class="text-xs font-medium">No has seleccionado servicios aún.</p>
                            <p class="text-[10px] opacity-75 mt-1">Elige del catálogo para comenzar.</p>
                        </div>
                    @else
                        <!-- List selected services -->
                        <div class="space-y-3 max-h-60 overflow-y-auto mb-4 pr-1">
                            @foreach($selectedServices as $selService)
                                <div class="flex items-center justify-between p-2.5 bg-[#f8f5fa] rounded-lg border border-gray-50 group">
                                    <div class="flex-1 pr-3">
                                        <h4 class="font-semibold text-xs text-[#2c1a36] line-clamp-1">{{ $selService->name }}</h4>
                                        <span class="text-[10px] text-gray-400">{{ $selService->duration }} min</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs font-bold text-[#c791e8]">${{ number_format($selService->price, 2) }}</span>
                                        <button wire:click="toggleService({{ $selService->id }})" class="text-gray-400 hover:text-red-500 transition">
                                            <i class="fa-solid fa-xmark text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Stats summary -->
                        <div class="border-t border-gray-100 pt-4 space-y-2">
                            <div class="flex justify-between text-xs text-gray-500">
                                <span>Servicios totales</span>
                                <span class="font-semibold">{{ count($cart) }}</span>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500">
                                <span>Duración total</span>
                                <span class="font-semibold">{{ $formattedDuration }}</span>
                            </div>
                            <div class="flex justify-between text-base font-bold text-[#2c1a36] pt-2 border-t border-dashed border-gray-100">
                                <span>Total Estimado</span>
                                <span class="text-[#c791e8]">${{ number_format($totalPrice, 2) }}</span>
                            </div>
                        </div>

                        <!-- Continue Button -->
                        <button wire:click="nextStep" class="w-full mt-6 py-2.5 bg-[#c791e8] text-white font-semibold rounded-lg hover:bg-[#a66cc9] transition shadow-md shadow-purple-100 flex items-center justify-center gap-2">
                            Continuar a Agendar <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Step 2: Stylist, Date & Time Selection -->
    @if($step === 2)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side: Stylist Selection -->
            <div class="lg:col-span-1">
                <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm h-full">
                    <h3 class="font-bold text-lg text-[#2c1a36] border-b border-gray-100 pb-4 mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-user-tie text-[#c791e8]"></i>
                        1. Elige Estilista
                    </h3>

                    <div class="space-y-4">
                        @foreach($specialists as $stylist)
                            @php $isStylistSelected = $selectedSpecialistId == $stylist->id; @endphp
                            <div wire:click="$set('selectedSpecialistId', '{{ $stylist->id }}')" 
                                 class="p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 flex items-center gap-4 {{ $isStylistSelected ? 'border-[#c791e8] bg-[#f8f5fa]' : 'border-gray-150 hover:border-gray-300 bg-white' }}">
                                
                                <!-- Profile Photo with fallback -->
                                <div class="w-12 h-12 rounded-full overflow-hidden border border-gray-100 flex-shrink-0 bg-gray-50 flex items-center justify-center">
                                    @if($stylist->profile_photo_path)
                                        <img src="/storage/{{ $stylist->profile_photo_path }}" alt="{{ $stylist->name }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fa-solid fa-user-tie text-xl text-gray-300"></i>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <h4 class="font-bold text-sm text-[#2c1a36]">{{ $stylist->name }}</h4>
                                    <span class="text-xs text-gray-400">Estilista Profesional</span>
                                </div>

                                <div>
                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center {{ $isStylistSelected ? 'border-[#c791e8] bg-[#c791e8]' : 'border-gray-300' }}">
                                        @if($isStylistSelected)
                                            <i class="fa-solid fa-check text-[10px] text-white"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('selectedSpecialistId')
                        <span class="text-xs text-red-500 mt-2 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Middle / Right Side: Calendar & Time Slots -->
            <div class="lg:col-span-2">
                <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm h-full flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-lg text-[#2c1a36] border-b border-gray-100 pb-4 mb-5 flex items-center gap-2">
                            <i class="fa-regular fa-calendar-days text-[#c791e8]"></i>
                            2. Selecciona Fecha y Hora
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Date Selector -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Fecha de Cita</label>
                                <input wire:model.live="selectedDate" type="date" min="{{ date('Y-m-d') }}" 
                                       class="w-full p-2.5 bg-gray-50 border-gray-250 rounded-lg focus:ring-[#c791e8] focus:border-[#c791e8] text-sm text-[#2c1a36] font-semibold">
                                
                                <div class="mt-4 p-3 bg-[#f8f5fa] rounded-lg text-xs text-[#2c1a36] border border-gray-50 space-y-1">
                                    <div class="flex items-center gap-2 font-bold text-[#c791e8]">
                                        <i class="fa-solid fa-circle-info"></i> Horarios de la Estética
                                    </div>
                                    <p class="text-gray-500">Lunes a Viernes: 8:00 AM - 5:00 PM</p>
                                    <p class="text-[10px] text-red-500 font-medium">Sábados y Domingos: Cerrado</p>
                                </div>

                                @error('selectedDate')
                                    <span class="text-xs text-red-500 mt-2 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Time Slots Selector -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Horas Disponibles</label>
                                
                                @if(Carbon\Carbon::parse($selectedDate)->isWeekend())
                                    <div class="p-6 text-center bg-red-50 rounded-xl border border-red-100 text-red-600">
                                        <i class="fa-regular fa-circle-xmark text-2xl mb-2"></i>
                                        <p class="text-xs font-bold">Estética Cerrada</p>
                                        <p class="text-[10px] mt-1">Por favor elige un día de lunes a viernes.</p>
                                    </div>
                                @elseif(empty($timeSlots))
                                    <div class="p-6 text-center bg-gray-50 rounded-xl border border-gray-100 text-gray-400">
                                        <i class="fa-regular fa-clock text-2xl mb-2 text-[#c791e8]"></i>
                                        <p class="text-xs font-semibold">Elige una fecha para ver horarios.</p>
                                    </div>
                                @else
                                    <div class="grid grid-cols-3 gap-2">
                                        @foreach($timeSlots as $slot)
                                            @php 
                                                $slotTime = $slot['time'];
                                                $isAvailable = $slot['available'];
                                                $formattedSlot = Carbon\Carbon::parse($slotTime)->format('g:i A');
                                                $isTimeSelected = $selectedTime === $slotTime;
                                            @endphp
                                            @if($isAvailable)
                                                <button wire:click="$set('selectedTime', '{{ $slotTime }}')" 
                                                        class="py-2.5 rounded-lg text-xs font-bold text-center border transition {{ $isTimeSelected ? 'bg-[#c791e8] text-white border-[#c791e8] shadow-md shadow-purple-50' : 'bg-white hover:bg-gray-50 text-[#2c1a36] border-gray-200' }}">
                                                    {{ $formattedSlot }}
                                                </button>
                                            @else
                                                <button disabled 
                                                        class="py-2.5 rounded-lg text-xs font-bold text-center border bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed opacity-50">
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
                    </div>

                    <!-- Navigation Action Buttons -->
                    <div class="flex items-center justify-between border-t border-gray-100 pt-6 mt-8">
                        <button wire:click="previousStep" class="px-5 py-2 border border-gray-300 text-gray-600 font-semibold rounded-lg hover:bg-gray-50 transition text-xs flex items-center gap-2">
                            <i class="fa-solid fa-arrow-left"></i> Regresar
                        </button>
                        
                        <button wire:click="nextStep" class="px-6 py-2 bg-[#c791e8] text-white font-semibold rounded-lg hover:bg-[#a66cc9] transition shadow-md shadow-purple-100 text-xs flex items-center gap-2">
                            Resumen de Cita <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Step 3: Confirmation Summary -->
    @if($step === 3)
        <div class="max-w-2xl mx-auto">
            <div class="bg-white border border-gray-100 rounded-2xl shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-[#c791e8] p-6 text-white text-center">
                    <i class="fa-regular fa-circle-check text-4xl mb-2"></i>
                    <h3 class="font-bold text-xl">Confirma tu Reservación</h3>
                    <p class="text-xs opacity-90 mt-1">Por favor verifica los detalles de tu cita a continuación</p>
                </div>

                <!-- Ticket details body -->
                <div class="p-6 space-y-6">
                    <!-- Date & Time Row -->
                    <div class="flex flex-col md:flex-row md:items-center gap-6 p-4 bg-[#f8f5fa] rounded-xl border border-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-[#c791e8] text-white flex items-center justify-center text-lg">
                                <i class="fa-regular fa-calendar-days"></i>
                            </div>
                            <div>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">Fecha</span>
                                <span class="font-bold text-sm text-[#2c1a36]">
                                    {{ Carbon\Carbon::parse($selectedDate)->isoFormat('dddd, D [de] MMMM') }}
                                </span>
                            </div>
                        </div>

                        <div class="h-8 w-[1px] bg-gray-200 hidden md:block"></div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-[#c791e8] text-white flex items-center justify-center text-lg">
                                <i class="fa-regular fa-clock"></i>
                            </div>
                            <div>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">Hora</span>
                                <span class="font-bold text-sm text-[#2c1a36]">
                                    {{ Carbon\Carbon::parse($selectedTime)->format('g:i A') }}
                                </span>
                            </div>
                        </div>

                        <div class="h-8 w-[1px] bg-gray-200 hidden md:block"></div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-[#c791e8] text-white flex items-center justify-center text-lg">
                                <i class="fa-solid fa-hourglass-half"></i>
                            </div>
                            <div>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">Duración</span>
                                <span class="font-bold text-sm text-[#2c1a36]">{{ $formattedDuration }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Stylist Card -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Estilista Designada</h4>
                        @php $stylistUser = $specialists->firstWhere('id', $selectedSpecialistId); @endphp
                        <div class="flex items-center gap-4 p-4 border border-gray-100 rounded-xl">
                            <div class="w-12 h-12 rounded-full overflow-hidden border border-gray-100 flex-shrink-0 bg-gray-50 flex items-center justify-center">
                                @if($stylistUser && $stylistUser->profile_photo_path)
                                    <img src="/storage/{{ $stylistUser->profile_photo_path }}" alt="{{ $stylistUser->name }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-user-tie text-xl text-gray-300"></i>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-[#2c1a36]">{{ $stylistUser ? $stylistUser->name : 'No seleccionada' }}</h4>
                                <span class="text-xs text-gray-400">Estilista Profesional</span>
                            </div>
                        </div>
                    </div>

                    <!-- Services list -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Servicios Solicitados</h4>
                        <div class="border border-gray-100 rounded-xl divide-y divide-gray-100">
                            @foreach($selectedServices as $service)
                                <div class="flex items-center justify-between p-3.5 text-sm">
                                    <div>
                                        <span class="font-semibold text-[#2c1a36]">{{ $service->name }}</span>
                                        <span class="text-[10px] text-gray-400 ml-2">({{ $service->duration }} min)</span>
                                    </div>
                                    <span class="font-bold text-[#c791e8]">${{ number_format($service->price, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Totals -->
                    <div class="border-t border-gray-100 pt-4 flex items-center justify-between">
                        <span class="text-sm font-semibold text-gray-500">Monto Total Estimado</span>
                        <span class="text-xl font-bold text-[#c791e8]">${{ number_format($totalPrice, 2) }}</span>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="p-6 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <button wire:click="previousStep" class="px-5 py-2 border border-gray-300 text-gray-600 font-semibold rounded-lg hover:bg-gray-100 transition text-xs flex items-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i> Regresar
                    </button>
                    
                    <button wire:click="saveAppointment" wire:loading.attr="disabled" class="px-6 py-2.5 bg-[#c791e8] text-white font-semibold rounded-lg hover:bg-[#a66cc9] transition shadow-md shadow-purple-100 text-xs flex items-center gap-2">
                        <span wire:loading.remove>
                            Confirmar y Agendar Cita <i class="fa-solid fa-circle-check ml-1"></i>
                        </span>
                        <span wire:loading class="flex items-center gap-2">
                            <i class="fa-solid fa-spinner animate-spin"></i> Agendando...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
