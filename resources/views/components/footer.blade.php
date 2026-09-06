<footer class="front-footer">
    <div class="front-footer-grid">
        <div class="front-footer-brand">
            <div>
                <img src="{{ asset('assets/Logo-1.png') }}" alt="">
                <span>
                    <strong>HypeStore</strong>
                    <small>Plataforma de compras e vendas</small>
                </span>
            </div>
            <p>Um espaço simples para comprar e anunciar produtos eletrônicos.</p>
        </div>

        <div>
            <h2>Conta</h2>
            <a href="{{ route('profile.edit') }}">Meu perfil</a>
            <a href="{{ route('produtos.manage') }}">Meus produtos</a>
            <a href="{{ route('history.purchases') }}">Histórico de compras</a>
            <a href="{{ route('history.sales') }}">Histórico de vendas</a>
        </div>

        <div>
            <h2>Navegação</h2>
            <a href="{{ route('produtos.index') }}">Produtos</a>
            <a href="{{ route('cart.index') }}">Carrinho</a>
            <a href="{{ route('help') }}#sobre">Sobre nós</a>
            <a href="{{ route('help') }}#contato">Ajuda e contato</a>
        </div>
    </div>

    <div class="front-footer-bottom">
        <span>&copy; {{ now()->year }} HypeStore</span>
        <span>
            <a href="{{ route('help') }}#privacidade">Privacidade</a>
            <a href="{{ route('help') }}#termos">Termos de uso</a>
            <a href="{{ route('help') }}#seguranca">Segurança</a>
        </span>
    </div>
</footer>
