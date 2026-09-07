<header>
    <nav x-data="{ open: false }" class="navbar">
        @php
            $rotulosCategorias = [
                'Tv' => 'TV',
                'Pc' => 'PC',
                'Games' => 'Games',
                'Hardware' => 'Hardware',
                'Relogio' => 'Relógios',
                'Celular' => 'Celular',
                'Audio' => 'Áudio',
                'Perifericos' => 'Periféricos',
                'GiftCard' => 'Gift Cards',
                'Cameras' => 'Câmeras',
                'Casa' => 'Casa',
                'Eletrodomestico' => 'Eletrodomésticos',
                'Outros' => 'Outros',
            ];
        @endphp

        <div class="navbar-logo">
            <a href="{{ route('dashboard') }}">
                <x-application-logo />
            </a>
        </div>

        <ul class="navbar-links">
            @auth
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                </li>
            @endauth

            <li>
                <x-dropdown align="left" width="48">
                    <x-slot name="trigger">
                        <button type="button" class="flex items-center gap-2 whitespace-nowrap transition hover:text-green-400">
                            <span>Departamentos</span>

                            <svg class="!h-4 !w-4 transition-transform" :class="{ 'rotate-180': open }" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.22 7.22a.75.75 0 0 1 1.06 0L10 10.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('produtos.index')">
                            Todos os produtos
                        </x-dropdown-link>

                        <div class="my-1 border-t border-gray-200"></div>

                        @forelse ($categorias as $categoria)
                            <x-dropdown-link :href="route('produtos.index', ['categoria' => $categoria->id])" :class="(string) request('categoria') === (string) $categoria->id ? 'bg-gray-100 font-semibold' : ''">
                                {{ $rotulosCategorias[$categoria->nome] ?? $categoria->nome }}
                            </x-dropdown-link>
                        @empty
                            <span class="block px-4 py-2 text-sm text-gray-500">
                                Nenhuma categoria cadastrada
                            </span>
                        @endforelse
                    </x-slot>
                </x-dropdown>
            </li>

            <li>
                <a href="#">Mais Vendidos</a>
            </li>

            <li>
                <a href="#">Ajuda</a>
            </li>
        </ul>

        <div class="navbar-icons">
            <x-search-icon />
            <x-heart-icon />
            <x-cart-icon />

            @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button type="button" class="nav-user-button">
                            <x-user-icon class="nav-user-icon" />
                            <span class="nav-user-name">{{ Auth::user()->nome }}</span>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Perfil
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                Sair
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            @else
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="nav-auth-link">Entrar</a>
                @endif

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="nav-auth-link nav-auth-link-register">Cadastrar</a>
                @endif
            @endauth
        </div>
    </nav>
</header>