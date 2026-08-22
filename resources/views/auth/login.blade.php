<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="login-signin">
        <h1 class="login-signin-tittle">Não possui uma conta?</h1>
        <span class="login-signin-text">Venha fazer parte desta comunidade incrível, junte-se a nós por aqui!</span>
        <x-painted-area-button class="login-signin-btn">
            <a class="" href="{{ route('register') }}">
                {{ __('Criar Conta') }}
            </a>
        </x-painted-area-button>
    </div>

    <form method="POST" action="{{ route('login') }}" class="login-form">
        @csrf

        <h1 class="login-tittle">CONECTE-SE</h1>

        <div class="login-mid">
            <!-- Email Address -->
            <div class="login-fields">


                <x-text-input class="login-input" id="email" type="email" name="email" :value="old('email')" placeholder=" " required autofocus autocomplete="username" />
                <x-input-label for="email" :value="__('Email')" class="login-label" />

                <x-input-error :messages="$errors->get('email')" class="mt-2" />

            </div>


            <div class="login-password-group">
                <!-- Password -->
                <div class="login-fields">

                    <x-text-input class="login-input" id="password" type="password" name="password" placeholder=" " required autocomplete="current-password" />
                    <x-input-label for="password" :value="__('Senha')" class="login-label" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />

                </div>
                <div class="login-esqueceu">
                    @if (Route::has('password.request'))
                    <a class="" href="{{ route('password.request') }}">
                        {{ __('Esqueceu a senha?') }}
                    </a>
                    @endif
                </div>
            </div>

            <!-- Continue Conectado -->
            <div class="login-check">
                <label for="remember_me" class="login-check-label">
                    <input id="remember_me" type="checkbox" class="login-check-box" name="remember">
                    <span class="login-check-text">{{ __('Continue Conectado') }}</span>
                </label>
            </div>

        </div>
        <x-nonpainted-area-button class="login-btn">
            {{ __('Entrar') }}
        </x-nonpainted-area-button>
    </form>
</x-guest-layout>