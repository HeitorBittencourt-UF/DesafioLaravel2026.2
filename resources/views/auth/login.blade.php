<x-guest-layout>
    <!-- Parte escrita : Criar Conta -->
    <div class="guest-signin guest-signin-left">
        <h1 class="guest-signin-tittle">Não possui uma conta?</h1>
        <span class="guest-signin-text">Venha fazer parte desta comunidade incrível, junte-se a nós por aqui!</span>
        <x-painted-area-button :href="route('register')">
            {{ __('Criar Conta') }}
        </x-painted-area-button>
    </div>
    <!-- Parte de formulário : Logar -->
    <form method="POST" action="{{ route('login') }}" class="guest-form guest-form-right">
        @csrf

        <h1 class="guest-tittle">CONECTE-SE</h1>
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div class="guest-mid">
            <!-- Email -->
            <div class="guest-fields">
                <x-text-input class="guest-input" id="email" type="email" name="email" :value="old('email')" placeholder=" " required autofocus autocomplete="username" />
                <x-input-label for="email" :value="__('Email')" class="guest-label" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="guest-password-group">
                <!-- Senha -->
                <div class="guest-fields">
                    <x-text-input class="guest-input" id="password" type="password" name="password" placeholder=" " required autocomplete="current-password" />
                    <x-input-label for="password" :value="__('Senha')" class="guest-label" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <!-- Esqueceu a senha -->
                <div class="guest-esqueceu">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">
                            {{ __('Esqueceu a senha?') }}
                        </a>
                    @endif
                </div>
            </div>

        </div>
        <x-nonpainted-area-button>
            {{ __('Entrar') }}
        </x-nonpainted-area-button>
    </form>
</x-guest-layout>
