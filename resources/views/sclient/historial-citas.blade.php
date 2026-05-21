<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#2c1a36] leading-tight">
            {{ __('Este es tu historial de citas, ' . Auth::user()->name) }}
        </h2>
    </x-slot>

    <div class="py-12 text-[#2c1a36]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-purple-50/50">
                
                <!-- Header Title -->
                <div class="mb-8">
                    <h1 class="font-bold text-2xl tracking-tight flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-[#c791e8]"></i>
                        Historial de Citas
                    </h1>
                    <p class="text-xs text-gray-500 mt-1">Aquí puedes ver el registro de tus visitas completadas en nuestro salón</p>
                </div>

                @if($completedAppointments->isEmpty())
                    <!-- Empty State -->
                    <div class="flex flex-col items-center justify-center py-20 bg-[#f8f5fa] rounded-2xl border border-dashed border-purple-100">
                        <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center shadow-sm mb-4">
                            <i class="fa-solid fa-receipt text-2xl text-[#c791e8]"></i>
                        </div>
                        <h3 class="font-bold text-lg">Aún no tienes un historial de citas</h3>
                        <p class="text-xs text-gray-400 mt-1 max-w-sm text-center px-4">
                            Tus citas finalizadas o canceladas aparecerán aquí como registro histórico.
                        </p>
                    </div>
                @else
                    <!-- Completed Appointments Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($completedAppointments as $appointment)
                            @php
                                $totalPrice = $appointment->services->sum('price');
                                $totalDuration = $appointment->services->sum('duration');
                                $formattedDate = Carbon\Carbon::parse($appointment->scheduled_date)->isoFormat('dddd, D [de] MMMM [de] YYYY');
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
                            <div class="bg-white border border-gray-150 rounded-xl shadow-sm overflow-hidden flex flex-col justify-between hover:border-purple-200 transition-all duration-200">
                                
                                <!-- Card Header -->
                                <div class="bg-[#fcfbfe] p-4 border-b border-gray-100 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-regular fa-calendar text-xs text-[#c791e8]"></i>
                                        <span class="text-xs font-bold capitalize text-gray-700">{{ $formattedDate }}</span>
                                    </div>
                                    @if($appointment->status === 'cancelled' || $appointment->status === 'cancelada')
                                        <span class="px-2.5 py-0.5 bg-red-50 text-red-700 text-[10px] font-bold rounded-full uppercase tracking-wider">
                                            Cancelada
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 bg-green-100 text-green-800 text-[10px] font-bold rounded-full uppercase tracking-wider">
                                            Completada
                                        </span>
                                    @endif
                                </div>

                                <!-- Card Body -->
                                <div class="p-4 space-y-4">
                                    <!-- Time and Stylist info -->
                                    <div class="flex items-center justify-between border-b border-gray-50 pb-3">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-regular fa-clock text-xs text-[#c791e8]"></i>
                                            <span class="text-xs font-bold text-gray-700">{{ $formattedTime }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-right">
                                            <span class="text-[9px] text-gray-400 block font-semibold">ESTILISTA</span>
                                            <span class="text-xs font-bold text-gray-600">{{ $appointment->specialist->user->name }}</span>
                                        </div>
                                    </div>

                                    <!-- Services List -->
                                    <div>
                                        <span class="text-[9px] font-bold uppercase tracking-wider text-gray-400 block mb-2">Servicios Brindados</span>
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
                                        Duración total: <span class="font-bold text-gray-600">{{ $durationText }}</span>
                                    </div>
                                    <div class="font-bold text-gray-700 text-sm">
                                        @if($appointment->status === 'cancelled' || $appointment->status === 'cancelada')
                                            Monto Cancelado: <span class="text-red-500 line-through">${{ number_format($totalPrice, 2) }}</span>
                                        @else
                                            Monto Pagado: <span class="text-[#c791e8]">${{ number_format($totalPrice, 2) }}</span>
                                        @endif
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