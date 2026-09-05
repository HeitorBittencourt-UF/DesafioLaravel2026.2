<x-app-layout>
    @php
        $displayName = $user->nome ?? $user->name ?? 'Usuário';

        $initial = \Illuminate\Support\Str::upper(
            \Illuminate\Support\Str::substr($displayName, 0, 1)
        );

        $accountType = ($user->tipo ?? 'usuario') === 'administrador'
            ? 'Administrador'
            : 'Cliente';
    @endphp

    <div class="profile-page">
        <main class="profile-shell">
            <header class="profile-page-header">
                <div>
                    <span class="profile-eyebrow">MINHA CONTA</span>

                    <h1 class="profile-page-title">PERFIL</h1>

                    <p class="profile-page-subtitle">
                        Gerencie seus dados pessoais, sua senha e as configurações da conta.
                    </p>
                </div>

                <a href="{{ route('dashboard') }}" class="profile-back-link">
                    <svg
                        aria-hidden="true"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>

                    Voltar ao painel
                </a>
            </header>

            <section class="profile-summary" aria-label="Resumo da conta">
                <div class="profile-avatar" aria-hidden="true">
                    {{ $initial }}
                </div>

                <div class="profile-summary-info">
                    <span class="profile-summary-label">
                        CONTA HYPESTORE
                    </span>

                    <h2>{{ $displayName }}</h2>
                    <p>{{ $user->email }}</p>
                </div>

                <div class="profile-account-badges">
                    <span class="profile-account-type">
                        {{ $accountType }}
                    </span>

                    <span class="profile-account-status">
                        <span aria-hidden="true"></span>
                        Conta ativa
                    </span>
                </div>
            </section>

            <div class="profile-grid">
                <article class="profile-card">
                    @include(
                        'profile.partials.update-profile-information-form'
                    )
                </article>

                <article class="profile-card">
                    @include(
                        'profile.partials.update-password-form'
                    )
                </article>

                <article class="profile-card profile-card-danger">
                    @include(
                        'profile.partials.delete-user-form'
                    )
                </article>
            </div>
        </main>
    </div>
</x-app-layout>