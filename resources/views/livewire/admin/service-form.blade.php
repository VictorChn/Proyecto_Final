<div>
    <x-dialog-modal wire:model.live="showModal">
        <x-slot name="title">
            {{ $serviceId ? 'Editar Servicio' : 'Agregar nuevo servicio' }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <x-label for="name" value="Nombre del Servicio" />
                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" />
                    <x-input-error for="name" class="mt-2" />
                </div>

                <div>
                    <x-label for="description" value="Descripción" />
                    <textarea id="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3" wire:model="description"></textarea>
                    <x-input-error for="description" class="mt-2" />
                </div>

                <div>
                    <x-label for="category" value="Categoría" />
                    <x-input id="category" type="text" class="mt-1 block w-full" wire:model="category" />
                    <x-input-error for="category" class="mt-2" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-label for="price" value="Precio ($)" />
                        <x-input id="price" type="number" step="0.01" class="mt-1 block w-full" wire:model="price" />
                        <x-input-error for="price" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="duration" value="Duración (minutos)" />
                        <x-input id="duration" type="number" step="5" class="mt-1 block w-full" wire:model="duration" />
                        <x-input-error for="duration" class="mt-2" />
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showModal', false)" wire:loading.attr="disabled">
                Cancelar
            </x-secondary-button>

            <x-button class="ms-3" wire:click="save" wire:loading.attr="disabled">
                Guardar
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
