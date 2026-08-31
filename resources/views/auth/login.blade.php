<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <!-- Parte escrita : Criar Conta -->
    <div class="guest-signin guest-signin-left">
        <h1 class="guest-signin-tittle">Não possui uma conta?</h1>
        <span class="guest-signin-text">Venha fazer parte desta comunidade incrível, junte-se a nós por aqui!</span>
        <x-painted-area-button>
            <a class="" href="{{ route('register') }}">
                {{ __('Criar Conta') }}
            </a>
        </x-painted-area-button>
    </div>
    <!-- Parte de formulário : Logar -->
    <form method="POST" action="{{ route('login') }}" class="guest-form guest-form-right">
        @csrf

        <h1 class="guest-tittle">CONECTE-SE</h1>

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
                    <a class="" href="{{ route('password.request') }}">
                        {{ __('Esqueceu a senha?') }}
                    </a>
                    @endif
                </div>
            </div>

            <!-- Continuar Conectado -->
            <div class="guest-check">
                <label for="remember_me" class="guest-check-label">
                    <input id="remember_me" type="checkbox" class="guest-check-box" name="remember">
                    <span class="guest-check-text">{{ __('Continue Conectado') }}</span>
                </label>
            </div>

        </div>
        <x-nonpainted-area-button class="font-montserrat bg-[#42B9A6] text-[15px] font-bold transition-all duration-300 ease-in-out hover:scale-110 hover:bg-[#52C8B5]">
            {{ __('Entrar') }}
        </x-nonpainted-area-button>
    </form>
</x-guest-layout>