<x-guest-layout>
    <!-- Colorido: Voltar -->
    <div class="guest-forgot-info">
        <h1 class="guest-signin-tittle">Lembrou a Senha?</h1>
        <span class="guest-signin-text">Se você lembrou de suas credenciais, volte para a página de login!</span>
        <x-painted-area-button>
            <a href="{{ route('login') }}">
                {{ __('Voltar ao Login') }}
            </a>
        </x-painted-area-button>
    </div>

    <!--Formulário:Envio de E-mail -->
    <form method="POST" action="{{ route('password.email') }}" class="guest-forgot-form">
        @csrf

        <h1 class="guest-tittle">RECUPERAR</h1>

        <span class="guest-forgot-text">
            Esqueceu sua senha? Sem problemas. Informe seu endereço de e-mail e enviaremos um link para você redefiní-la.
        </span>

        <!-- Aviso de e-mail enviado -->
        <x-auth-session-status :status="session('status')" />

        <div class="guest-mid">
            <!-- E-mail -->
            <div class="guest-fields">
                <x-text-input id="email" class="guest-input" type="email" name="email" :value="old('email')" required autofocus placeholder=" " />
                <x-input-label for="email" :value="__('Email')" class="guest-label" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
        </div>

        <x-nonpainted-area-button>
            {{ __('Enviar Link') }}
        </x-nonpainted-area-button>
    </form>
</x-guest-layout>