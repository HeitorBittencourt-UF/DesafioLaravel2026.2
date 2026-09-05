<section
    class="profile-section"
    aria-labelledby="profile-password-title"
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
                    d="M16.5 10.5V6.75a4.5 4.5 0 00-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0119.5 12.75v6A2.25 2.25 0 0117.25 21H6.75a2.25 2.25 0 01-2.25-2.25v-6a2.25 2.25 0 012.25-2.25z"
                />
            </svg>
        </div>

        <div>
            <h2
                id="profile-password-title"
                class="profile-card-title"
            >
                SEGURANÇA
            </h2>

            <p class="profile-card-description">
                Use uma senha forte e diferente das utilizadas em outros sites.
            </p>
        </div>
    </header>

    <form
        method="post"
        action="{{ route('password.update') }}"
        class="profile-form"
    >
        @csrf
        @method('put')

        <div class="profile-field">
            <label
                for="update_password_current_password"
                class="profile-label"
            >
                Senha atual
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
                        d="M16.5 10.5V6.75a4.5 4.5 0 00-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0119.5 12.75v6A2.25 2.25 0 0117.25 21H6.75a2.25 2.25 0 01-2.25-2.25v-6a2.25 2.25 0 012.25-2.25z"
                    />
                </svg>

                <input
                    id="update_password_current_password"
                    name="current_password"
                    type="password"
                    class="profile-input"
                    autocomplete="current-password"
                >
            </div>

            <x-input-error
                :messages="$errors->updatePassword->get('current_password')"
                class="profile-error"
            />
        </div>

        <div class="profile-password-grid">
            <div class="profile-field">
                <label
                    for="update_password_password"
                    class="profile-label"
                >
                    Nova senha
                </label>

                <div class="profile-input-wrapper">
                    <input
                        id="update_password_password"
                        name="password"
                        type="password"
                        class="profile-input"
                        autocomplete="new-password"
                    >
                </div>

                <x-input-error
                    :messages="$errors->updatePassword->get('password')"
                    class="profile-error"
                />
            </div>

            <div class="profile-field">
                <label
                    for="update_password_password_confirmation"
                    class="profile-label"
                >
                    Confirmar nova senha
                </label>

                <div class="profile-input-wrapper">
                    <input
                        id="update_password_password_confirmation"
                        name="password_confirmation"
                        type="password"
                        class="profile-input"
                        autocomplete="new-password"
                    >
                </div>

                <x-input-error
                    :messages="$errors->updatePassword->get(
                        'password_confirmation'
                    )"
                    class="profile-error"
                />
            </div>
        </div>

        <div class="profile-form-actions">
            <button
                type="submit"
                class="profile-primary-button"
            >
                Atualizar senha
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="profile-saved-message"
                    role="status"
                >
                    Senha atualizada
                </p>
            @endif
        </div>
    </form>
</section>