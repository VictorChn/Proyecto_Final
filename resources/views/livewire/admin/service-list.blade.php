<div class="p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-gray-800">Catálogo de Servicios</h2>
        <button wire:click="createService" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            Agregar servicio
        </button>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Nombre
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Categoría
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Precio
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Duración (min)
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($services as $service)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap font-bold">{{ $service->name }}</p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                <span class="relative">{{ $service->category }}</span>
                            </span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">${{ number_format($service->price, 2) }}</p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ $service->duration }} min</p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                            <button wire:click="editService({{ $service->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fa-solid fa-pen-to-square text-xl"></i>
                            </button>
                            <button wire:click="confirmDelete({{ $service->id }})" class="text-red-600 hover:text-red-900">
                                <i class="fa-solid fa-trash text-xl"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                            No hay servicios registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
            {{ $services->links() }}
        </div>
    </div>
    
    @livewire('admin.service-form')
</div>
