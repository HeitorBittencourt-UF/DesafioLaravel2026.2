<section
    class="profile-section"
    aria-labelledby="profile-information-title"
>
    <header class="profile-card-header">
        <div class="profile-card-icon" aria-hidden="true">
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.1a7.5 7.5 0 0115 0A17.9 17.9 0 0112 21.75a17.9 17.9 0 01-7.5-1.65z"
                />
            </svg>
        </div>

        <div>
            <h2
                id="profile-information-title"
                class="profile-card-title"
            >
                INFORMAÇÕES PESSOAIS
            </h2>

            <p class="profile-card-description">
                Atualize o nome e o e-mail vinculados à sua conta.
            </p>
        </div>
    </header>

    <form
        id="send-verification"
        method="post"
        action="{{ route('verification.send') }}"
    >
        @csrf
    </form>

    <form
        method="post"
        action="{{ route('profile.update') }}"
        class="profile-form"
    >
        @csrf
        @method('patch')

        <div class="profile-field">
            <label for="nome" class="profile-label">
                Nome completo
            </label>

            <div class="profile-input-wrapper">
                <svg
                    aria-hidden="true"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.1a7.5 7.5 0 0115 0A17.9 17.9 0 0112 21.75a17.9 17.9 0 01-7.5-1.65z"
                    />
                </svg>

                <input
                    id="nome"
                    name="nome"
                    type="text"
                    class="profile-input"
                    value="{{ old('nome', $user->nome) }}"
                    required
                    autofocus
                    autocomplete="name"
                >
            </div>

            <x-input-error
                class="profile-error"
                :messages="$errors->get('nome')"
            />
        </div>

        <div class="profile-field">
            <label for="email" class="profile-label">
                E-mail
            </label>

            <div class="profile-input-wrapper">
                <svg
                    aria-hidden="true"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M21.75 6.75v10.5A2.25 2.25 0 0119.5 19.5h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0l-8.5 5.67a2.25 2.25 0 01-2.5 0l-8.5-5.67"
                    />
                </svg>

                <input
                    id="email"
                    name="email"
                    type="email"
                    class="profile-input"
                    value="{{ old('email', $user->email) }}"
                    required
                    autocomplete="username"
                >
            </div>

            <x-input-error
                class="profile-error"
                :messages="$errors->get('email')"
            />

            @if (
                $user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail
                && ! $user->hasVerifiedEmail()
            )
                <div class="profile-verification-warning">
                    <p>
                        Seu endereço de e-mail ainda não foi verificado.
                    </p>

                    <button
                        form="send-verification"
                        type="submit"
                    >
                        Reenviar e-mail de verificação
                    </button>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <p class="profile-success-message">
                        Um novo link de verificação foi enviado.
                    </p>
                @endif
            @endif
        </div>

        <div class="profile-form-actions">
            <button
                type="submit"
                class="profile-primary-button"
            >
                Salvar alterações
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="profile-saved-message"
                    role="status"
                >
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
                            d="M4.5 12.75l6 6 9-13.5"
                        />
                    </svg>

                    Dados salvos
                </p>
            @endif
        </div>
    </form>
</section>