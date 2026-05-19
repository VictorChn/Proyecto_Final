<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#2c1a36] leading-tight">
            {{ __('Selecciona los servicios de tu cita, ' . Auth::user()->name) }}
        </h2>
    </x-slot>

    <div class="py-12">

      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-[#f8f5fa] overflow-hidden shadow-xl sm:rounded-lg p-6">
          @livewire('client.select-services')
        </div>
      </div>

    </div>

</x-app-layout>