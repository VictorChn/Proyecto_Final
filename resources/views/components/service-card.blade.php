@props(['service'])

<div class="bg-white rounded-xl shadow-md border border-[#f0dfff] overflow-hidden hover:shadow-lg hover:border-[#c791e8] transition-all flex flex-col h-full group">
    <div class="p-6 flex-grow">
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-xl font-bold text-[#2c1a36] leading-tight pr-4">{{ $service->name }}</h3>
            <span class="bg-[#F6EBFF] text-[#a66cc9] font-bold px-3 py-1 rounded-full whitespace-nowrap border border-[#c791e8] group-hover:bg-[#a66cc9] group-hover:text-white transition-colors">
                ${{ number_format($service->price, 2) }}
            </span>
        </div>
        <p class="text-sm text-gray-500 mb-4 flex items-center gap-2 font-medium">
            <i class="fa-regular fa-clock text-[#c791e8]"></i> {{ $service->duration }} min
        </p>
        <p class="text-gray-600 text-sm leading-relaxed">
            {{ $service->description }}
        </p>
    </div>
    <div class="p-4 bg-[#faf5ff] border-t border-[#f0dfff]">
        <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2 bg-[#a66cc9] text-white font-semibold rounded-md hover:bg-[#8e52b1] transition-colors shadow-sm">
            Agendar Cita
        </a>
    </div>
</div>
