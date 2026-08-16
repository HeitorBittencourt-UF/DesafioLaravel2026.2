<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    
    <form method="POST" action="{{ route('register') }}" class="register-form">
        @csrf
        
        <h1 class="register-tittle">CRIE SUA CONTA</h1>
        
        <div class="register-mid">
            <!-- Email Address -->
            <div class="register-fields">
                
                <x-text-input class="register-input" id="email" type="email" name="email" :value="old('email')" placeholder=" " required autofocus autocomplete="username" />
                <x-input-label for="email" :value="__('Email')" class="register-label"/>
                
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                
            </div>
            
            
            <div class="register-password-group">
                <!-- Password -->
                <div class="register-fields">
                    
                    <x-text-input  class="register-input" id="password" type="password" name="password" placeholder=" " required autocomplete="current-password" />
                    <x-input-label for="password" :value="__('Senha')" class="register-label"/>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    
                </div>
                
            </div>
            
            <!-- Continue Conectado -->
            <div class="register-check">
                <label for="remember_me" class="register-check-label">
                    <input id="remember_me" type="checkbox" class="register-check-box" name="remember">
                    <span class="register-check-text">{{ __('Continue Conectado') }}</span>
                </label>
            </div>
            
        </div>
        <x-primary-button class="register-btn">
            {{ __('Criar') }}
        </x-primary-button>
    </form>

    <div class="register-signin">
        <h1 class="register-signin-tittle">Seja Bem-Vindo!</h1>
        <span class="register-signin-text">Já possui uma conta? Conecte-se para obter os benefícios!</span>
        <x-primary-button class="register-signin-btn">
            <a class="" href="{{ route('login') }}">
                {{ __('Login') }}
            </a>
        </x-primary-button>
    </div>
</x-guest-layout>
