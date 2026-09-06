<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'HypeStore') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/Logo-1.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Gothic&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="front-landing-body">
    <x-navbar />

    <main>
        <section class="front-hero">
            <div class="front-hero-content">
                <span class="front-eyebrow">Compre. Venda. Conecte.</span>
                <h1>Eletrônicos para todos os momentos.</h1>
                <p>Encontre produtos anunciados pela comunidade ou publique os seus em um só lugar.</p>
                <div>
                    <a href="{{ route('produtos.index') }}" class="front-button front-button-primary">Explorar produtos</a>
                    @if (auth()->user()->tipo !== 'administrador')
                        <a href="{{ route('produtos.create') }}" class="front-button front-button-light">Anunciar produto</a>
                    @endif
                </div>
            </div>
            <div class="front-hero-art" aria-hidden="true">
                <span></span>
                <img src="{{ asset('assets/Logo-1.png') }}" alt="">
            </div>
        </section>

        @php
            $rotulos = [
                'Tv' => 'TV', 'Pc' => 'PC', 'Games' => 'Games', 'Hardware' => 'Hardware',
                'Relogio' => 'Relógios', 'Celular' => 'Celular', 'Audio' => 'Áudio',
                'Perifericos' => 'Periféricos', 'GiftCard' => 'Gift Cards', 'Cameras' => 'Câmeras',
                'Casa' => 'Casa', 'Eletrodomestico' => 'Eletrodomésticos', 'Outros' => 'Outros',
            ];
        @endphp

        <section class="front-home-categories" aria-label="Categorias de produtos">
            <div class="front-shell">
                @foreach ($categorias as $categoria)
                    <a href="{{ route('produtos.index', ['categoria' => $categoria->id]) }}">
                        <span>
                            @switch($categoria->nome)
                                @case('Tv')
                                    <x-tv-icon />
                                    @break
                                @case('Pc')
                                    <x-pc-icon />
                                    @break
                                @case('Games')
                                    <x-games-icon />
                                    @break
                                @case('Hardware')
                                    <x-hardware-icon />
                                    @break
                                @case('Relogio')
                                    <x-watch-icon />
                                    @break
                                @case('Celular')
                                    <x-cellphone-icon />
                                    @break
                                @case('Audio')
                                    <x-sound-icon />
                                    @break
                                @case('Perifericos')
                                    <x-mouse-icon />
                                    @break
                                @case('GiftCard')
                                    <x-giftcard-icon />
                                    @break
                                @case('Cameras')
                                    <x-photo-icon />
                                    @break
                                @case('Casa')
                                    <x-house-icon />
                                    @break
                                @case('Eletrodomestico')
                                    <x-appliance-icon />
                                    @break
                                @default
                                    <x-tag-icon />
                            @endswitch
                        </span>
                        <small>{{ $rotulos[$categoria->nome] ?? $categoria->nome }}</small>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="front-home-produtos">
            <div class="front-shell">
                @forelse ($categorias->filter(fn ($categoria) => $categoria->produtos->isNotEmpty()) as $categoria)
                    <section class="front-home-category-section">
                        <div class="front-section-heading">
                            <div>
                                <span class="front-eyebrow">Departamento</span>
                                <h2>{{ $rotulos[$categoria->nome] ?? $categoria->nome }}</h2>
                            </div>
                            <a href="{{ route('produtos.index', ['categoria' => $categoria->id]) }}">Ver todos</a>
                        </div>

                        <div class="front-produto-grid">
                            @foreach ($categoria->produtos as $produto)
                                <article class="front-produto-card">
                                    <a href="{{ route('produtos.show', $produto->id) }}" class="front-produto-image-box">
                                        <img src="{{ asset($produto->foto) }}" alt="{{ $produto->nome }}">
                                    </a>
                                    <div class="front-produto-content">
                                        <span class="front-produto-category">{{ $rotulos[$categoria->nome] ?? $categoria->nome }}</span>
                                        <h2><a href="{{ route('produtos.show', $produto->id) }}">{{ $produto->nome }}</a></h2>
                                        <span class="front-produto-price">R$ {{ number_format((float) $produto->preco, 2, ',', '.') }}</span>
                                        <div class="front-produto-actions">
                                            <a href="{{ route('produtos.show', $produto->id) }}" class="front-button front-button-ghost">Ver produto</a>
                                            @if (auth()->user()->tipo !== 'administrador')
                                                <a href="{{ route('compra.show', $produto->id) }}" class="front-button front-button-primary">Comprar</a>
                                            @endif
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @empty
                    <div class="front-empty-state">
                        <h2>Nenhum produto disponível</h2>
                        <p>No momento, não há produtos de outros usuários disponíveis.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </main>

    <x-footer />
</body>
</html>
