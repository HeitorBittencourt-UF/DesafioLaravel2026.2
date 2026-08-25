<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('register') }}" class="guest-form guest-form-left">
        @csrf

        <h1 class="guest-tittle">CRIE SUA CONTA</h1>

        <div class="guest-mid">
            <!-- Email Address -->
            <div class="guest-fields">
                <x-text-input class="guest-input" id="email" type="email" name="email" :value="old('email')" placeholder=" " required autofocus autocomplete="username" />
                <x-input-label for="email" :value="__('Email')" class="guest-label" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="guest-password-group">
                <!-- Password -->
                <div class="guest-fields">
                    <x-text-input class="guest-input" id="password" type="password" name="password" placeholder=" " required autocomplete="current-password" />
                    <x-input-label for="password" :value="__('Senha')" class="guest-label" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
            </div>

            <!-- Continue Conectado -->
            <div class="guest-check">
                <label for="remember_me" class="guest-check-label">
                    <input id="remember_me" type="checkbox" class="guest-check-box" name="remember">
                    <span class="guest-check-text">{{ __('Continue Conectado') }}</span>
                </label>
            </div>

        </div>
        <x-nonpainted-area-button class="font-montserrat bg-[#42B9A6] text-[15px] font-bold transition-all duration-300 ease-in-out hover:scale-110 hover:bg-[#52C8B5]">
            {{ __('Criar') }}
        </x-nonpainted-area-button>
    </form>

    <div class="guest-signin guest-signin-right">
        <h1 class="guest-signin-tittle">Seja Bem-Vindo!</h1>
        <span class="guest-signin-text">Já possui uma conta? Conecte-se para obter os benefícios!</span>
        <x-painted-area-button>
            <a class="" href="{{ route('login') }}">
                {{ __('Login') }}
            </a>
        </x-painted-area-button>
    </div>
</x-guest-layout>