<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#2c1a36] leading-tight">
            {{ __('Bienvenido ' . Auth::user()->name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#f8f5fa] overflow-hidden shadow-xl sm:rounded-lg p-6 mb-20">
                <div class="flex items-center justify-between mb-10">
                  <h1 class="font-bold text-2xl text-[#2c1a36]" id="Citas">Citas</h1>
                  <a href="{{ route('agendar') }}" class="px-7 py-2 bg-[#c791e8] text-white font-semibold rounded-lg hover:bg-[#a66cc9] transition">Agendar Cita</a>
                </div>
                <div class="flex items-center justify-center mb-10 py-14">
                    <h3 class="text-[#2c1a36] font-semibold text-lg">No tienes citas proximas</h3>
                </div>
            </div>

            <div class="bg-[#f8f5fa] overflow-hidden shadow-xl sm:rounded-lg p-6">
              <h1 class="font-bold text-2xl text-[#2c1a36]" id="Historial">Historial de Citas</h1>
              <div class="flex items-center justify-center mb-10 py-14">
                <h3 class="text-[#2c1a36] font-semibold text-lg">Aún no tienes un historial de citas.</h3>
              </div>
            </div>
        </div>
    </div>
</x-app-layout>