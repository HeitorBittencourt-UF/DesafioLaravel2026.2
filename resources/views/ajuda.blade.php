<x-app-layout>
    <main class="front-page">
        <div class="front-shell front-help-shell">
            <header class="front-page-heading">
                <div>
                    <span class="front-eyebrow">Central de ajuda</span>
                    <h1>Como podemos ajudar?</h1>
                    <p>Informações rápidas sobre a HypeStore e o fluxo de compra.</p>
                </div>
            </header>

            <section class="front-help-grid">
                <article id="sobre">
                    <span>01</span>
                    <h2>Sobre nós</h2>
                    <p>A HypeStore conecta pessoas interessadas em comprar e vender produtos eletrônicos.</p>
                </article>
                <article>
                    <span>02</span>
                    <h2>Como comprar</h2>
                    <p>Escolha um produto, confira os detalhes, adicione ao carrinho e avance pelas etapas de endereço e
                        pagamento.</p>
                </article>
                <article>
                    <span>03</span>
                    <h2>Como vender</h2>
                    <p>Acesse Meus produtos, selecione Novo produto e preencha as informações do anúncio.</p>
                </article>
                <article id="seguranca">
                    <span>04</span>
                    <h2>Segurança</h2>
                    <p>Os dados são validados pelo backend. O pagamento permanece pendente até a confirmação do
                        provedor.</p>
                </article>
            </section>

            <section class="front-help-contact" id="contato">
                <div>
                    <span class="front-eyebrow">Contato</span>
                    <h2>Ainda precisa de ajuda?</h2>
                    <p>Descreva sua dúvida e informe um e-mail para retorno.</p>
                </div>

                <form action="{{ route('ajuda.send') }}" method="POST">
                    @csrf

                    @if (session('success'))
                        <div class="front-alert-success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="front-alert-error">
                            @foreach ($errors->all() as $erro)
                                <p>{{ $erro }}</p>
                            @endforeach
                        </div>
                    @endif

                    <label class="front-field">
                        <span>Nome</span>
                        <input name="nome" value="{{ old('nome') }}" required>
                    </label>
                    <label class="front-field">
                        <span>E-mail</span>
                        <input name="email" type="email" value="{{ old('email') }}" required>
                    </label>
                    <label class="front-field">
                        <span>Mensagem</span>
                        <textarea name="mensagem" rows="5" required>{{ old('mensagem') }}</textarea>
                    </label>
                    <button type="submit" class="front-button front-button-primary">Enviar mensagem</button>
                </form>
            </section>

            <section class="front-legal-grid">
                <article id="privacidade">
                    <h2>Privacidade</h2>
                    <p>Esta seção receberá a política final de tratamento de dados.</p>
                </article>
                <article id="termos">
                    <h2>Termos de uso</h2>
                    <p>Esta seção receberá os termos finais da plataforma.</p>
                </article>
            </section>
        </div>
    </main>

    <x-footer />
</x-app-layout>
