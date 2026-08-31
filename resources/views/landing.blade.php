<!DOCTYPE html>
<html class="landing-html" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Título e Imagem da Aba  -->
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/Logo-1.png') }}">

    <!-- Fontes -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<!-- Navbar -->

<body class=" ">
    <header class="not-has-[nav]:hidden">
        @if (Route::has('login'))
        <!-- Logo -->
        <nav class="navbar">
            <div class="navbar-logo">
                <x-application-logo></x-application-logo>
            </div>
            <!-- Ancoragens da navbar -->
            <ul class="navbar-links">
                <li><a href="#">Departamentos</a></li>
                <li><a href="#">Mais Curtidos</a></li>
                <li><a href="#">Mais Vendidos</a></li>
                <li><a href="#">Ofertas</a></li>
                <li><a href="#">Ajuda</a></li>
            </ul>
            <!-- Icones da Navbar -->
            <div class="navbar-icons">
                <x-user-icon class="w-1 h-1" />
                <x-cart-icon />
                <x-heart-icon />
                <x-search-icon />
            </div>
        </nav>
        <!-- VER DEPOIS OQ É / Codigo do Laravel -->
        <!-- <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
                        >
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav> -->
        @endif
    </header>
    <!-- VER DEPOIS OQ É / Codigo do Laravel -->

    <div class="lading-container">
        <!-- Hero Section - Talvez futuro carrossel -->
        <div class="lading-hero">
            <x-hero-hypestore />
        </div>
        <!-- Área de Categorias -->
        <div>

            <section class="category-bar">
                <div class="category-container">

                    <!-- TV -->
                    <x-category-item label="TV" href="#">
                        <x-tv-icon />
                    </x-category-item>

                    <!-- PC -->
                    <x-category-item label="PC" href="#">
                        <x-pc-icon />
                    </x-category-item>

                    <!-- Games -->
                    <x-category-item label="Games" href="#">
                        <x-games-icon />
                    </x-category-item>

                    <!-- Hardware -->
                    <x-category-item label="Hardware" href="#">
                        <x-hardware-icon />
                    </x-category-item>

                    <!-- Relógio -->
                    <x-category-item label="Relógios" href="#">
                        <x-watch-icon />
                    </x-category-item>

                    <!-- Celular -->
                    <x-category-item label="Celular" href="#">
                        <x-cellphone-icon />
                    </x-category-item>

                    <!-- Áudio -->
                    <x-category-item label="Áudio" href="#">
                        <x-sound-icon />
                    </x-category-item>

                    <!-- Periféricos -->
                    <x-category-item label="Periféricos" href="#">
                        <x-mouse-icon />
                    </x-category-item>

                    <!-- GiftCard -->
                    <x-category-item label="GiftCards" href="#">
                        <x-giftcard-icon />
                    </x-category-item>

                    <!-- Cameras -->
                    <x-category-item label="Câmeras" href="#">
                        <x-photo-icon />
                    </x-category-item>

                    <!-- Casa -->
                    <x-category-item label="Casa" href="#">
                        <x-house-icon />
                    </x-category-item>

                    <!-- Eletrodomésticos -->
                    <x-category-item label="Eletrodomésticos" href="#">
                        <x-appliance-icon />
                    </x-category-item>

                </div>
            </section>

        </div>

        <!-- Amostragem dos produtos -->
        <section class="products-section">
            <!-- Título -->
            <div class="section-header">
                <h2 class="section-title">
                    <x-tag-icon class="section-title-icon" />
                    Principais Ofertas
                </h2>
                <!-- Ver Mais -->
                <a href="#" class="see-more-btn">
                    ver mais
                    <x-arrow-icon class="see-more-icon" />
                </a>
            </div>

            <!-- Carrossel / Lista de Produtos -->
            <div class="carousel-wrapper">
                <!-- Seta Esquerda -->
                <button class="carousel-arrow arrow-left" aria-label="Anterior">
                    <x-arrow-icon class="arrow-icon-left" />
                </button>

                <!-- Container Cards -->
                <div class="products-container">

                    <!-- Card 1: iPhone 13 -->
                    <div class="product-card">
                        <div class="card-top">
                            <!-- Avaliacoes -->
                            <div class="rating-info">
                                <span class="star-icon">★</span>
                                <span class="rating-score">4.7</span>
                                <span class="rating-count">(218)</span>
                            </div>
                            <!-- Ícones do Card -->
                            <div class="card-actions">
                                <button class="action-btn icon-favorite" aria-label="Favoritar">
                                    <x-heart-icon />
                                </button>
                                <button class="action-btn icon-cart" aria-label="Adicionar ao carrinho">
                                    <x-cart-icon />
                                </button>
                            </div>
                        </div>
                        <!-- container da imagem -->
                        <div class="product-image-box">
                            <img src="https://via.placeholder.com/160" alt="Apple iPhone 13" class="product-image" />
                        </div>
                        <!-- titulo do produto -->
                        <h3 class="product-title">
                            Apple iPhone 13 (128gb) Branco + Acessórios
                        </h3>
                        <!-- containeir dos valores -->
                        <div class="product-price-box">
                            <span class="old-price">R$ 3.138</span>
                            <span class="current-price">R$ 2.761,44</span>
                            <span class="discount-badge">-12%</span>
                        </div>
                    </div>

                    <!-- Card 2: Water Cooler -->
                    <div class="product-card">
                        <!-- Avaliacoes -->
                        <div class="card-top">
                            <div class="rating-info">
                                <span class="star-icon">★</span>
                                <span class="rating-score">5.0</span>
                                <span class="rating-count">(108)</span>
                            </div>
                            <!-- Ícones do Card -->
                            <div class="card-actions">
                                <button class="action-btn icon-favorite" aria-label="Favoritar">
                                    <x-heart-icon />
                                </button>
                                <button class="action-btn icon-cart" aria-label="Adicionar ao carrinho">
                                    <x-cart-icon />
                                </button>
                            </div>
                        </div>
                        <!-- container da imagem -->
                        <div class="product-image-box">
                            <img src="https://via.placeholder.com/160" alt="Water Cooler" class="product-image" />
                        </div>
                        <!-- titulo do produto -->
                        <h3 class="product-title">
                            Water Cooler Montech LightFlow, ARGB, 360mm, AMD
                        </h3>
                        <!-- containeir dos valores -->
                        <div class="product-price-box">
                            <span class="old-price">R$ 455,54</span>
                            <span class="current-price">R$ 209,99</span>
                            <span class="discount-badge">-48%</span>
                        </div>
                    </div>

                    <!-- Card 3: GTA V -->
                    <div class="product-card">
                        <div class="card-top">
                            <!-- Avaliacoes -->
                            <div class="rating-info">
                                <span class="star-icon">★</span>
                                <span class="rating-score">3.2</span>
                                <span class="rating-count">(321)</span>
                            </div>
                            <!-- Ícones do Card -->
                            <div class="card-actions">
                                <button class="action-btn icon-favorite" aria-label="Favoritar">
                                    <x-heart-icon />
                                </button>
                                <button class="action-btn icon-cart" aria-label="Adicionar ao carrinho">
                                    <x-cart-icon />
                                </button>
                            </div>
                        </div>
                        <!-- container da imagem -->
                        <div class="product-image-box">
                            <img src="https://via.placeholder.com/160" alt="GTA V" class="product-image" />
                        </div>
                        <!-- titulo do produto -->
                        <h3 class="product-title">
                            Jogo Grand Theft Auto V - PS5
                        </h3>
                        <!-- containeir dos valores -->
                        <div class="product-price-box">
                            <span class="old-price">R$ 449,00</span>
                            <span class="current-price">R$ 418,41</span>
                            <span class="discount-badge">-48%</span>
                        </div>
                    </div>

                    <!-- Card 4: Teclado Redragon -->
                    <div class="product-card">
                        <!-- Avaliacoes -->
                        <div class="card-top">
                            <div class="rating-info">
                                <span class="star-icon">★</span>
                                <span class="rating-score">4.9</span>
                                <span class="rating-count">(1809)</span>
                            </div>
                            <!-- Ícones do Card -->
                            <div class="card-actions">
                                <button class="action-btn icon-favorite" aria-label="Favoritar">
                                    <x-heart-icon />
                                </button>
                                <button class="action-btn icon-cart" aria-label="Adicionar ao carrinho">
                                    <x-cart-icon />
                                </button>
                            </div>
                        </div>
                        <!-- container da imagem -->
                        <div class="product-image-box">
                            <img src="https://via.placeholder.com/160" alt="Teclado Gamer" class="product-image" />
                        </div>
                        <!-- titulo do produto -->
                        <h3 class="product-title">
                            Teclado Magnetico Gamer Redragon Fizz, RGB...
                        </h3>
                        <!-- containeir dos valores -->
                        <div class="product-price-box">
                            <span class="old-price">R$ 411,75</span>
                            <span class="current-price">R$ 229,99</span>
                            <span class="discount-badge">-6%</span>
                        </div>
                    </div>

                </div>

                <!-- Seta Direita -->
                <button class="carousel-arrow arrow-right" aria-label="Próximo">
                    <x-arrow-icon />
                </button>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <!-- Esquerda: Sobre a HypeStore -->
            <div class="footer-brand">
                <!-- Logo -->
                <div class="footer-logo">
                    <x-application-logo class="footer-logo-icon" />
                    <div class="footer-logo-text">
                        <h2>E-Commerce Digital</h2>
                        <p>Plataforma de compras e vendas</p>
                    </div>
                </div>
                <!-- Descricao -->
                <p class="footer-description">
                    Sendo a ponte para o comércio de todos os tipos de eletrônicos. Faça suas compras e vendas aqui, sem limitações.
                </p>
                <!-- Icones das Redes -->
                <div class="footer-socials">
                    <a href="https://www.instagram.com/codejr/" class="social-icon"><x-instagram-icon /></a>
                    <a href="https://br.linkedin.com/company/codejr" class="social-icon"><x-linkedin-icon /></a>
                    <a href="https://github.com/desenvolvedoresCodeJr" class="social-icon"><x-git-icon /></a>
                    <a href="https://www.codejr.com.br/" class="social-icon"><x-web-icon /></a>
                </div>
            </div>

            <!-- Meio: Empresa -->
            <div class="footer-nav-col">
                <h3>Empresa</h3>
                <ul>
                    <li><a href="#">Sobre nós</a></li>
                    <li><a href="#">Parceiros</a></li>
                    <li><a href="#">Contato</a></li>
                </ul>
            </div>

            <!-- Direita: Áreas -->
            <div class="footer-nav-col">
                <h3>Áreas</h3>
                <ul>
                    <li><a href="#">Departamentos</a></li>
                    <li><a href="#">Ofertas</a></li>
                    <li><a href="#">Mais Curtidos</a></li>
                    <li><a href="#">Mais Vendidos</a></li>
                    <li><a href="#">Ajuda</a></li>
                </ul>
            </div>
        </div>

        <!-- Linha Inferior -->
        <div class="footer-bottom">
            <p>&copy; 2026 todos os direitos reservados</p>
            <div class="footer-bottom-links">
                <a href="#">Política de privacidade</a>
                <a href="#">Termos de Serviço</a>
                <a href="#">Política de Cookies</a>
                <a href="#">Segurança</a>
            </div>
        </div>
    </footer>

    <!-- Codigos do Laravel VER DPS -->
    <!-- @if (Route::has('login'))
    <div class="h-14.5 hidden lg:block"></div>
    @endif -->
</body>

</html>