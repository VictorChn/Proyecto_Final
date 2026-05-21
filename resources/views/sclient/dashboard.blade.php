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

                @livewire('client.active-appointments')

            </div>
        </div>
    </div>
</x-app-layout>