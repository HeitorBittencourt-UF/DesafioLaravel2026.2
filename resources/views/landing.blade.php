<!DOCTYPE html>

<html class="landing-html" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Título e Imagem da Aba -->
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/Logo-1.png') }}">

    <!-- Fontes -->
    <link rel="preconnect" href="https://fonts.bunny.net">

    <link
        href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600"
        rel="stylesheet"
    />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

</head>

<body>

    <!-- Navbar -->
    <x-navbar />

    <div class="lading-container">

        <!-- Hero Section -->
        <div class="lading-hero">

            <x-hero-hypestore />

        </div>


        <!-- Área de Categorias -->
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

                <!-- Câmeras -->
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


        <!-- Mapa dos ícones das categorias -->
        @php
            $iconesCategorias = [
                'Tv' => 'tv-icon',
                'Pc' => 'pc-icon',
                'Games' => 'games-icon',
                'Hardware' => 'hardware-icon',
                'Relogio' => 'watch-icon',
                'Celular' => 'cellphone-icon',
                'Audio' => 'sound-icon',
                'Perifericos' => 'mouse-icon',
                'GiftCard' => 'giftcard-icon',
                'Cameras' => 'photo-icon',
                'Casa' => 'house-icon',
                'Eletrodomestico' => 'appliance-icon',
                'Outros' => 'tag-icon',
            ];
        @endphp


        <!-- Produtos separados por categoria -->
        @foreach ($categorias as $categoria)

            @if ($categoria->produtos->isNotEmpty())

                <section
                    class="products-section"
                    id="categoria-{{ $categoria->id }}"
                >

                    <!-- Cabeçalho da categoria -->
                    <div class="section-header">

                        <h2 class="section-title">

                            <x-dynamic-component
                                :component="$iconesCategorias[$categoria->nome] ?? 'tag-icon'"
                                class="section-title-icon"
                            />

                            {{ $categoria->nome }}

                        </h2>

                        <a href="#" class="see-more-btn">

                            ver mais

                            <x-arrow-icon class="see-more-icon" />

                        </a>

                    </div>


                    <!-- Produtos da categoria -->
                    <div class="products-container">

                        @foreach ($categoria->produtos->take(4) as $produto)

                            <div class="product-card">

                                <!-- Topo do card -->
                                <div class="card-top">

                                    <div class="card-actions">

                                        <button
                                            class="action-btn icon-cart"
                                            aria-label="Adicionar ao carrinho"
                                        >

                                            <x-cart-icon />

                                        </button>

                                    </div>

                                </div>


                                <!-- Imagem -->
                                <div class="product-image-box">

                                    <img
                                        src="{{ asset($produto->foto) }}"
                                        alt="{{ $produto->nome }}"
                                        class="product-image"
                                    >

                                </div>


                                <!-- Nome -->
                                <h3 class="product-title">

                                    {{ $produto->nome }}

                                </h3>


                                <!-- Preço -->
                                <div class="product-price-box">

                                    <span class="current-price">

                                        R$ {{ number_format($produto->preco, 2, ',', '.') }}

                                    </span>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </section>

            @endif

        @endforeach

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


                <!-- Descrição -->
                <p class="footer-description">

                    Sendo a ponte para o comércio de todos os tipos de eletrônicos.
                    Faça suas compras e vendas aqui, sem limitações.

                </p>


                <!-- Redes sociais -->
                <div class="footer-socials">

                    <a
                        href="https://www.instagram.com/codejr/"
                        class="social-icon"
                    >
                        <x-instagram-icon />
                    </a>

                    <a
                        href="https://br.linkedin.com/company/codejr"
                        class="social-icon"
                    >
                        <x-linkedin-icon />
                    </a>

                    <a
                        href="https://github.com/desenvolvedoresCodeJr"
                        class="social-icon"
                    >
                        <x-git-icon />
                    </a>

                    <a
                        href="https://www.codejr.com.br/"
                        class="social-icon"
                    >
                        <x-web-icon />
                    </a>

                </div>

            </div>


            <!-- Meio: Empresa -->
            <div class="footer-nav-col">

                <h3>Empresa</h3>

                <ul>

                    <li>
                        <a href="#">Sobre nós</a>
                    </li>

                    <li>
                        <a href="#">Parceiros</a>
                    </li>

                    <li>
                        <a href="#">Contato</a>
                    </li>

                </ul>

            </div>


            <!-- Direita: Áreas -->
            <div class="footer-nav-col">

                <h3>Áreas</h3>

                <ul>

                    <li>
                        <a href="#">Departamentos</a>
                    </li>

                    <li>
                        <a href="#">Ofertas</a>
                    </li>

                    <li>
                        <a href="#">Mais Curtidos</a>
                    </li>

                    <li>
                        <a href="#">Mais Vendidos</a>
                    </li>

                    <li>
                        <a href="#">Ajuda</a>
                    </li>

                </ul>

            </div>

        </div>


        <!-- Linha Inferior -->
        <div class="footer-bottom">

            <p>
                &copy; 2026 todos os direitos reservados
            </p>

            <div class="footer-bottom-links">

                <a href="#">Política de privacidade</a>

                <a href="#">Termos de Serviço</a>

                <a href="#">Política de Cookies</a>

                <a href="#">Segurança</a>

            </div>

        </div>

    </footer>

</body>

</html>