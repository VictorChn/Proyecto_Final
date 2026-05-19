<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#2c1a36] leading-tight">
            {{ __('Bienvenido ' . Auth::user()->name) }}
        </h2>
    </x-slot>

    <div class="py-12 text-[#2c1a36]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-purple-50/50">
                
                <!-- Header Title and Agendar Button -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                    <div>
                        <h1 class="font-bold text-2xl tracking-tight flex items-center gap-2">
                            <i class="fa-regular fa-calendar-check text-[#c791e8]"></i>
                            Mis Citas Próximas
                        </h1>
                        <p class="text-xs text-gray-500 mt-1">Aquí puedes ver el estado y los detalles de tus reservaciones activas</p>
                    </div>
                    <a href="{{ route('seleccionar-servicios') }}" class="px-6 py-2.5 bg-[#c791e8] text-white font-semibold rounded-lg hover:bg-[#a66cc9] transition shadow-md shadow-purple-100 flex items-center justify-center gap-2 text-sm self-start sm:self-auto">
                        <i class="fa-solid fa-plus text-xs"></i> Agendar Cita
                    </a>
                </div>

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

                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>