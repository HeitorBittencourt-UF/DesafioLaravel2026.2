<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Parte Formulário : Criar Conta -->
    <form method="POST" action="{{ route('register') }}" class="guest-form guest-form-left">
        @csrf

        <h1 class="guest-tittle">CRIE SUA CONTA</h1>

        <div class="guest-mid">

            <!-- Nome -->
            <div class="guest-fields">
                <x-text-input class="guest-input" id="nome" type="text" name="nome" :value="old('nome')" placeholder=" " required autofocus autocomplete="name" />
                <x-input-label for="nome" :value="__('Nome')" class="guest-label" />
                <x-input-error :messages="$errors->get('nome')" class="mt-2" />
            </div>

            <!-- CPF -->
            <div class="guest-fields">
                <x-text-input class="guest-input" id="cpf" type="text" name="cpf" :value="old('cpf')" placeholder=" " required autocomplete="off" maxlength="14" />
                <x-input-label for="cpf" :value="__('CPF')" class="guest-label" />
                <x-input-error :messages="$errors->get('cpf')" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="guest-fields">
                <x-text-input class="guest-input" id="email" type="email" name="email" :value="old('email')" placeholder=" " required autocomplete="username" />
                <x-input-label for="email" :value="__('Email')" class="guest-label" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Telefone -->
            <div class="guest-fields">
                <x-text-input class="guest-input" id="telefone" type="tel" name="telefone" :value="old('telefone')" placeholder=" " required autocomplete="tel" maxlength="15" />
                <x-input-label for="telefone" :value="__('Telefone')" class="guest-label" />
                <x-input-error :messages="$errors->get('telefone')" class="mt-2" />
            </div>

            <!-- Data de Nascimento -->
            <div class="guest-fields">
                <x-text-input class="guest-input" id="data_nascimento" type="date" name="data_nascimento" :value="old('data_nascimento')" required />
                <x-input-label for="data_nascimento" :value="__('Data de Nascimento')" class="guest-label" />
                <x-input-error :messages="$errors->get('data_nascimento')" class="mt-2" />
            </div>

            <div class="guest-password-group">
                <!-- Senha -->
                <div class="guest-fields">
                    <x-text-input class="guest-input" id="password" type="password" name="password" placeholder=" " required autocomplete="new-password" />
                    <x-input-label for="password" :value="__('Senha')" class="guest-label" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirmar Senha -->
                <div class="guest-fields mt-4">
                    <x-text-input class="guest-input" id="password_confirmation" type="password" name="password_confirmation" placeholder=" " required autocomplete="new-password" />
                    <x-input-label for="password_confirmation" :value="__('Confirmar Senha')" class="guest-label" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>

        </div>

        <x-nonpainted-area-button>
            {{ __('Criar') }}
        </x-nonpainted-area-button>
    </form>

    <!-- Parte Escrita : Login -->
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