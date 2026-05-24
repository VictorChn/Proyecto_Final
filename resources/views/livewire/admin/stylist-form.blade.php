<div>
    <x-dialog-modal wire:model.live="showModal">
        <x-slot name="title">
            {{ $userId ? 'Editar Estilista' : 'Agregar una nueva estilista' }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <x-label for="name" value="Nombre Completo" />
                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" />
                    <x-input-error for="name" class="mt-2" />
                </div>

                <div>
                    <x-label for="email" value="Correo Electrónico" />
                    <x-input id="email" type="email" class="mt-1 block w-full" wire:model="email" />
                    <x-input-error for="email" class="mt-2" />
                </div>

                <div>
                    <x-label for="phone" value="Teléfono" />
                    <x-input id="phone" type="text" class="mt-1 block w-full" wire:model="phone" />
                    <x-input-error for="phone" class="mt-2" />
                </div>

                <!-- Campo Contraseña Anterior (Solo cuando estamos editando) -->
                @if($userId)
                    <div x-data="{ showCurr: false }">
                        <x-label for="current_password" value="Contraseña Anterior (Requerido para guardar cambios de contraseña)" />
                        <div class="relative">
                            <x-input id="current_password" ::type="showCurr ? 'text' : 'password'" class="mt-1 block w-full pr-10" wire:model="current_password" />
                            <button type="button" @click="showCurr = !showCurr" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i class="fa-solid" :class="showCurr ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        <x-input-error for="current_password" class="mt-2" />
                    </div>
                @endif

                <!-- Nueva Contraseña -->
                <div x-data="{ showPass: false }">
                    <x-label for="password" value="{{ $userId ? 'Nueva Contraseña (dejar en blanco para no cambiar)' : 'Contraseña' }}" />
                    <div class="relative">
                        <x-input id="password" ::type="showPass ? 'text' : 'password'" class="mt-1 block w-full pr-10" wire:model="password" />
                        <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <i class="fa-solid" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    <x-input-error for="password" class="mt-2" />
                </div>

                <!-- Confirmar Nueva Contraseña -->
                @if(!$userId || $password || $current_password)
                    <div x-data="{ showConfirm: false }">
                        <x-label for="password_confirmation" value="Confirmar Contraseña" />
                        <div class="relative">
                            <x-input id="password_confirmation" ::type="showConfirm ? 'text' : 'password'" class="mt-1 block w-full pr-10" wire:model="password_confirmation" />
                            <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i class="fa-solid" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        <x-input-error for="password_confirmation" class="mt-2" />
                    </div>
                @endif
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
