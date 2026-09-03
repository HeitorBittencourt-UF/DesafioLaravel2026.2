<x-guest-layout>
    <!-- Lado Esquerdo: Área Informativa -->
    <div class="guest-reset-info">
        <h1 class="guest-signin-tittle">Nova Senha</h1>
        <span class="guest-signin-text">Crie uma nova senha para garantir a segurança da sua conta.</span>
        <x-painted-area-button>
            <a href="{{ route('login') }}">
                {{ __('Voltar ao Login') }}
            </a>
        </x-painted-area-button>
    </div>

    <!-- Lado Direito: Formulário -->
    <form method="POST" action="{{ route('password.store') }}" class="guest-reset-form">
        @csrf

        <!-- Token Obrigatório -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <h1 class="guest-tittle">REDEFINIR</h1>

        <div class="guest-reset-mid">
            <!-- Email -->
            <div class="guest-fields">
                <x-text-input id="email" class="guest-input" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" placeholder=" " />
                <x-input-label for="email" :value="__('Email')" class="guest-label" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Nova Senha -->
            <div class="guest-fields">
                <x-text-input id="senha" class="guest-input" type="password" name="senha" required autocomplete="new-password" placeholder=" " />
                <x-input-label for="senha" :value="__('Nova Senha')" class="guest-label" />
                <x-input-error :messages="$errors->get('senha')" class="mt-2" />
            </div>

            <!-- Confirmar Nova Senha -->
            <div class="guest-fields">
                <x-text-input id="senha_confirmation" class="guest-input" type="password" name="senha_confirmation" required autocomplete="new-password" placeholder=" " />
                <x-input-label for="senha_confirmation" :value="__('Confirmar Senha')" class="guest-label" />
                <x-input-error :messages="$errors->get('senha_confirmation')" class="mt-2" />
            </div>
        </div>

        <!-- Botão de Envio -->
        <x-nonpainted-area-button class="font-montserrat bg-[#42B9A6] text-[15px] font-bold transition-all duration-300 ease-in-out hover:scale-110 hover:bg-[#52C8B5]">
            {{ __('Redefinir Senha') }}
        </x-nonpainted-area-button>
    </form>
</x-guest-layout>