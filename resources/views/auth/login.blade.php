<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="login-signin">

    </div>

    <form method="POST" action="{{ route('login') }}" class="login-form">
        @csrf

        <h1 class="login-tittle">CONECTE-SE</h1>

        <div class="login-mid">
            <!-- Email Address -->
            <div class="login-fields">

                
                <x-text-input class="login-input" id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-label for="email" :value="__('Email')" class="login-label"/>
                
                <x-input-error :messages="$errors->get('email')" class="mt-2" />

            </div>

            <!-- Password -->
            <div class="login-fields">

                
                <x-text-input  class="login-input" id="password" type="password" name="password" required autocomplete="current-password" />
                <x-input-label for="password" :value="__('Senha')" class="login-label"/>
                
                <x-input-error :messages="$errors->get('password')" class="mt-2" />

            </div>

            <!-- Continue Conectado -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="" name="remember">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Continue Conectado') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                <a class="" href="{{ route('password.request') }}">
                    {{ __('Esqueceu a senha?') }}
                </a>
                @endif
            </div>
        </div>
            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
    </form>
</x-guest-layout>