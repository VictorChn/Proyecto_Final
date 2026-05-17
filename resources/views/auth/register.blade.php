<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf

            <div>
                <h1 class="text-center text-2xl font-bold my-3">Crea tu cuenta en GIO & ANGIE</h1>
                <p class="text-center mb-4">Regístrate una sola vez para agendar de forma inmediata y activar tus recordatorios automáticos.</p>
            </div>

            <!-- Foto de perfil -->
            <div class="mb-4" x-data="{ photoName: null, photoPreview: null }">
                <x-label for="photo" value="{{ __('Foto de Perfil (Opcional)') }}" />
                
                <div class="flex items-center flex-col-reverse">
                    <input id="photo" type="file" name="photo" class="hidden" accept="image/png, image/jpeg"
                        x-ref="photo"
                        x-on:change="
                            photoName = $refs.photo.files[0].name;
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                photoPreview = e.target.result;
                            };
                            reader.readAsDataURL($refs.photo.files[0]);
                        " />

                        <!-- Vista previa circular -->
                        <div class="mt-3 flex items-center gap-4 flex-col">
                            <div x-show="photoPreview" style="display: none;">
                                <span class="block rounded-full size-36 bg-cover bg-no-repeat bg-center border-2 border-[#a66cc9]"
                                      x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                                </span>
                            </div>
        
                            <button type="button" class="px-4 py-2 bg-[#F6EBFF] text-[#a66cc9] font-semibold text-sm rounded-md border border-[#c791e8] hover:bg-[#e9d5ff] transition" x-on:click.prevent="$refs.photo.click()">
                                <i class="fa-solid fa-camera mr-2"></i> Subir Foto
                            </button>
                        </div>
                </div>

            </div>

            <div>
                <x-label for="name" value="{{ __('Nombre Completo') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Ej. María González" />
            </div>

            <div class="mt-4">
                <x-label for="phone" value="{{ __('Télefono Celular') }}" />
                <x-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" required autocomplete="phone" placeholder="Ej. 999 123 4567" />
            </div>
            
            <div class="mt-4">
                <x-label for="email" value="{{ __('Correo') }}" />
                <x-input id="email" class="block mt-1 w-full focus:border-[#a588b8]" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nombre@correo.com"/>
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Contraseña') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="Mínimo 8 caracteres"/>
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirmar Contraseña') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ms-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('¿Ya estas registrado?') }}
                </a>

                <x-button class="ms-4">
                    {{ __('Registrarse') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
