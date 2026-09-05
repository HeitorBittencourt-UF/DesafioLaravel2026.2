<section
    class="profile-section profile-danger-section"
    aria-labelledby="profile-delete-title"
>
    <header class="profile-card-header">
        <div
            class="profile-card-icon profile-card-icon-danger"
            aria-hidden="true"
        >
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="M14.74 9l-.35 9m-4.78 0L9.26 9m9.97-3.21L18.07 20.9A2.25 2.25 0 0115.82 23H8.18a2.25 2.25 0 01-2.25-2.1L4.77 5.79M8.5 5.5h7"
                />
            </svg>
        </div>

        <div>
            <h2
                id="profile-delete-title"
                class="profile-card-title"
            >
                EXCLUIR CONTA
            </h2>

            <p class="profile-card-description">
                Esta ação remove permanentemente sua conta e os dados vinculados a ela.
            </p>
        </div>
    </header>

    <button
        type="button"
        class="profile-danger-button"
        x-data=""
        x-on:click.prevent="
            $dispatch('open-modal', 'confirm-user-deletion')
        "
    >
        Excluir minha conta
    </button>

    <x-modal
        name="confirm-user-deletion"
        :show="$errors->userDeletion->isNotEmpty()"
        maxWidth="lg"
        focusable
    >
        <form
            method="post"
            action="{{ route('profile.destroy') }}"
            class="profile-delete-modal"
        >
            @csrf
            @method('delete')

            <div class="profile-modal-icon" aria-hidden="true">
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M12 9v3.75m9.3 3.38c1.16 2-.29 4.5-2.6 4.5H5.3c-2.31 0-3.75-2.5-2.6-4.5L9.4 4.53c1.15-2 4.04-2 5.2 0l6.7 11.6zM12 17.25h.01v.01H12v-.01z"
                    />
                </svg>
            </div>

            <h2 class="profile-modal-title">
                Tem certeza que deseja excluir sua conta?
            </h2>

            <p class="profile-modal-description">
                Essa ação não poderá ser desfeita. Digite sua senha para confirmar a exclusão permanente.
            </p>

            <div class="profile-field profile-modal-field">
                <label
                    for="delete_account_password"
                    class="profile-label"
                >
                    Senha atual
                </label>

                <input
                    id="delete_account_password"
                    name="password"
                    type="password"
                    class="profile-input profile-modal-input"
                    placeholder="Digite sua senha"
                    autocomplete="current-password"
                >

                <x-input-error
                    :messages="$errors->userDeletion->get('password')"
                    class="profile-error"
                />
            </div>

            <div class="profile-modal-actions">
                <button
                    type="button"
                    class="profile-cancel-button"
                    x-on:click="$dispatch('close')"
                >
                    Cancelar
                </button>

                <button
                    type="submit"
                    class="profile-danger-button profile-danger-button-solid"
                >
                    Excluir permanentemente
                </button>
            </div>
        </form>
    </x-modal>
</section>