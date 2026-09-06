<x-app-layout>
    <main class="front-page front-checkout-page">
        <div class="front-shell front-checkout-shell">
            @if ($etapa !== 'concluido')
                <header class="front-page-heading front-checkout-heading">
                    <div>
                        <span class="front-eyebrow">Finalizar pedido</span>
                        <h1>{{ $etapa === 'endereco' ? 'Endereço de entrega' : 'Pagamento' }}</h1>
                        <p>Confira os dados do pedido antes de avançar.</p>
                    </div>

                    <div class="front-checkout-steps" aria-label="Etapas da compra">
                        <span class="done"><b>✓</b><small>Carrinho</small></span>
                        <i class="done"></i>
                        <span @class([
                            'active' => $etapa === 'endereco',
                            'done' => $etapa === 'pagamento',
                        ])>
                            <b>{{ $etapa === 'pagamento' ? '✓' : '2' }}</b>
                            <small>Endereço</small>
                        </span>
                        <i @class(['done' => $etapa === 'pagamento'])></i>
                        <span @class(['active' => $etapa === 'pagamento'])>
                            <b>3</b><small>Pagamento</small>
                        </span>
                    </div>
                </header>

                @if ($errors->any())
                    <div class="front-alert-error">
                        @foreach ($errors->all() as $erro)
                            <p>{{ $erro }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="front-checkout-layout">
                    @if ($etapa === 'endereco')
                        <form action="{{ route('checkout.address.store') }}" method="POST" class="front-form-card">
                            @csrf

                            <div class="front-form-section-heading">
                                <span>1</span>
                                <div>
                                    <h2>Dados de entrega</h2>
                                    <p>Informe o local em que o pedido deverá ser entregue.</p>
                                </div>
                            </div>

                            <div class="front-form-grid">
                                <label class="front-field front-field-small">
                                    <span>CEP</span>
                                    <input name="cep" inputmode="numeric" value="{{ old('cep', $endereco?->cep) }}"
                                        placeholder="00000-000" required>
                                </label>

                                <label class="front-field front-field-wide">
                                    <span>Logradouro</span>
                                    <input name="logradouro" value="{{ old('logradouro', $endereco?->logradouro) }}"
                                        placeholder="Rua ou avenida" required>
                                </label>

                                <label class="front-field">
                                    <span>Número</span>
                                    <input name="numero" value="{{ old('numero', $endereco?->numero) }}"
                                        placeholder="123" required>
                                </label>

                                <label class="front-field">
                                    <span>Bairro</span>
                                    <input name="bairro" value="{{ old('bairro', $endereco?->bairro) }}"
                                        placeholder="Bairro" required>
                                </label>

                                <label class="front-field">
                                    <span>Cidade</span>
                                    <input name="cidade" value="{{ old('cidade', $endereco?->cidade) }}"
                                        placeholder="Cidade" required>
                                </label>

                                <label class="front-field">
                                    <span>Estado</span>
                                    <input name="estado" maxlength="2" value="{{ old('estado', $endereco?->estado) }}"
                                        placeholder="MG" required>
                                </label>

                                <label class="front-field front-field-wide">
                                    <span>Complemento (opcional)</span>
                                    <input name="complemento" value="{{ old('complemento', $endereco?->complemento) }}"
                                        placeholder="Apartamento, bloco ou referência">
                                </label>
                            </div>

                            <div class="front-form-actions">
                                <a href="{{ route('cart.index') }}" class="front-button front-button-ghost">Voltar</a>
                                <button type="submit" class="front-button front-button-primary">
                                    Continuar para pagamento
                                </button>
                            </div>
                        </form>
                    @else
                        <form action="{{ route('checkout.finish') }}" method="POST" class="front-form-card"
                            data-payment-form>
                            @csrf

                            <div class="front-form-section-heading">
                                <span>2</span>
                                <div>
                                    <h2>Dados do pagamento</h2>
                                    <p>Escolha uma forma de pagamento e confira os dados.</p>
                                </div>
                            </div>

                            <div class="front-payment-options">
                                <label @class(['active' => old('forma', 'cartao') === 'cartao'])>
                                    <input type="radio" name="forma" value="cartao" @checked(old('forma', 'cartao') === 'cartao')>
                                    Cartão
                                </label>
                                <label @class(['active' => old('forma') === 'pix'])>
                                    <input type="radio" name="forma" value="pix" @checked(old('forma') === 'pix')>
                                    Pix
                                </label>
                            </div>

                            <div class="front-form-grid" data-card-fields>
                                <label class="front-field front-field-wide">
                                    <span>Número do cartão</span>
                                    <input name="numero_cartao" inputmode="numeric" value="{{ old('numero_cartao') }}"
                                        placeholder="0000 0000 0000 0000" data-card-input>
                                </label>

                                <label class="front-field front-field-wide">
                                    <span>Nome impresso</span>
                                    <input name="nome_cartao" value="{{ old('nome_cartao') }}"
                                        placeholder="Nome completo" data-card-input>
                                </label>

                                <label class="front-field">
                                    <span>Validade</span>
                                    <input name="validade" value="{{ old('validade') }}" placeholder="MM/AA"
                                        data-card-input>
                                </label>

                                <label class="front-field">
                                    <span>CVV</span>
                                    <input name="cvv" inputmode="numeric" maxlength="4" placeholder="000"
                                        data-card-input>
                                </label>
                            </div>

                            <p class="front-payment-notice" data-pix-notice hidden>
                                O pedido será registrado como pendente até a confirmação pelo provedor de pagamento.
                            </p>

                            <div class="front-form-actions">
                                <a href="{{ route('checkout.address') }}"
                                    class="front-button front-button-ghost">Voltar</a>
                                <button type="submit" class="front-button front-button-primary">Finalizar
                                    pedido</button>
                            </div>
                        </form>
                    @endif

                    <aside class="front-order-summary">
                        <h2>Resumo da compra</h2>
                        <div>
                            <span>{{ $quantidadeProdutos }}
                                {{ $quantidadeProdutos === 1 ? 'produto' : 'produtos' }}</span>
                            <strong>R$ {{ number_format($total, 2, ',', '.') }}</strong>
                        </div>
                        <div><span>Frete</span><strong class="front-free-shipping">Grátis</strong></div>
                        <div class="front-order-total">
                            <span>Total</span>
                            <strong>R$ {{ number_format($total, 2, ',', '.') }}</strong>
                        </div>
                        <small>Revise os valores antes de finalizar.</small>
                    </aside>
                </div>
            @else
                <section class="front-success-card">
                    <span class="front-success-icon">✓</span>
                    <span class="front-eyebrow">Pedido recebido</span>
                    <h1>Pedido registrado</h1>
                    <p>
                        Seu pedido foi criado e está aguardando a confirmação do pagamento.
                        Você pode acompanhar os detalhes no histórico de compras.
                    </p>

                    @if ($venda)
                        <p>
                            Pedido #{{ $venda->id }} —
                            R$ {{ number_format((float) $venda->ValorTotal, 2, ',', '.') }}
                        </p>
                    @endif

                    <div>
                        <a href="{{ route('historico.compras') }}" class="front-button front-button-primary">
                            Ver minhas compras
                        </a>
                        <a href="{{ route('produtos.index') }}" class="front-button front-button-ghost">
                            Continuar comprando
                        </a>
                    </div>
                </section>
            @endif
        </div>
    </main>

    <x-footer />
</x-app-layout>
