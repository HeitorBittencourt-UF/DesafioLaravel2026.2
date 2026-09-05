<header>
    <nav x-data="{ open: false }" class="navbar">
        <!-- Logo -->
        <div class="navbar-logo">
            <a href="{{ route('dashboard') }}">
                <x-application-logo />
            </a>
        </div>

        <!-- Ancoragem -->
        <ul class="navbar-links">
            @auth
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        {{ __('Dashboard') }}
                    </a>
                </li>
            @endauth
            <li><a href="#">Departamentos</a></li>
            <li><a href="#">Mais Curtidos</a></li>
            <li><a href="#">Mais Vendidos</a></li>
            <li><a href="#">Ofertas</a></li>
            <li><a href="#">Ajuda</a></li>
        </ul>

        <!-- Ícones + Opcoes de Login -->
        <div class="navbar-icons">
            <x-search-icon />
            <x-heart-icon />
            <x-cart-icon />

            @auth
                <!-- Opcoes Logado -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="nav-user-button">
                            <x-user-icon class="nav-user-icon" />
                            <span class="nav-user-name">{{ Auth::user()->name }}</span>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            @else
                <!-- Cadastro para Visitantes -->
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="nav-auth-link">Log in</a>
                @endif
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="nav-auth-link nav-auth-link-register">Register</a>
                @endif
            @endauth

        </div>
    </nav>
</header>