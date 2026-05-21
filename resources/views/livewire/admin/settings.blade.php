<div class="text-[#2c1a36] max-w-4xl mx-auto py-8">

    <!-- ═══ HEADER ═══ -->
    <div class="mb-8">
        <h1 class="font-black text-2xl tracking-tight flex items-center gap-2">
            <i class="fa-solid fa-gears text-[#c791e8]"></i>
            Configuración del Sistema
        </h1>
        <p class="text-xs text-gray-400 mt-1">Configura los parámetros globales de la plataforma y tareas automáticas.</p>
    </div>

    <!-- ═══ FORM CONTAINER ═══ -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-[#fcf9ff] rounded-full -mr-10 -mt-10"></div>
        
        <div class="relative">
            <h2 class="font-bold text-base mb-4 flex items-center gap-2 text-[#2c1a36]">
                <i class="fa-solid fa-clock text-[#c791e8]"></i>
                Horario de Envío de Agendas Diarias
            </h2>

            <p class="text-xs text-gray-500 mb-6 leading-relaxed">
                Define la hora exacta del día en que el sistema procesará y enviará de manera automática los reportes de citas programadas para el día siguiente. Este ajuste afecta tanto al correo consolidado en PDF para ti (Administrador) como a las agendas individuales en PDF para cada estilista.
            </p>

            <form wire:submit.prevent="save" class="space-y-6 max-w-xl">
                <!-- Session Flash Message -->
                @if (session()->has('message'))
                    <div class="p-4 bg-green-50 border border-green-200 text-green-700 text-xs font-bold rounded-xl flex items-center gap-2 animate-fade-in-down">
                        <i class="fa-solid fa-circle-check text-green-500 text-sm"></i>
                        {{ session('message') }}
                    </div>
                @endif

                <!-- Select Hour Input -->
                <div>
                    <label for="notification_hour" class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">
                        Hora de Envío Diario
                    </label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-regular fa-clock text-gray-400"></i>
                        </div>
                        <select wire:model.defer="notification_hour" id="notification_hour" 
                            class="block w-full pl-10 pr-4 py-3 bg-[#f8f5fa] border-0 focus:ring-2 focus:ring-[#c791e8] rounded-xl text-xs font-bold text-[#2c1a36] transition-all">
                            @for ($h = 0; $h < 24; $h++)
                                @php
                                    $ampm = $h >= 12 ? 'PM' : 'AM';
                                    $displayH = $h % 12 ?: 12;
                                    $paddedH = str_pad($h, 2, '0', STR_PAD_LEFT);
                                    $label = "$paddedH:00 (" . str_pad($displayH, 2, '0', STR_PAD_LEFT) . ":00 $ampm)";
                                @endphp
                                <option value="{{ $h }}">{{ $label }}</option>
                            @endfor
                        </select>
                    </div>
                    @error('notification_hour') 
                        <span class="text-xs text-red-500 mt-2 block font-semibold">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Info Legend/Caption -->
                <div class="p-4 bg-[#f8f5fa] border-l-4 border-[#c791e8] rounded-r-xl text-xs text-gray-500 leading-relaxed">
                    <strong><i class="fa-solid fa-circle-info text-[#c791e8]"></i> Leyenda de ejecución:</strong> 
                    Las citas reflejadas serán las correspondientes al día calendario siguiente.
                </div>

                <!-- Action Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full sm:w-auto bg-[#c791e8] hover:bg-[#b57dd6] text-white font-bold py-3 px-8 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 flex items-center justify-center gap-2 text-xs">
                        <span wire:loading wire:target="save" class="border-2 border-white border-t-transparent rounded-full w-4 h-4 animate-spin"></span>
                        <i wire:loading.remove wire:target="save" class="fa-solid fa-floppy-disk"></i>
                        Guardar Configuración
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
