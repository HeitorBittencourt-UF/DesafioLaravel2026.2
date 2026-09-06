<x-app-layout>
    <main class="front-page">
        <div class="front-shell front-form-shell">
            <div class="front-breadcrumb">
                <a href="{{ route('produtos.manage') }}">
                    {{ auth()->user()->tipo === 'administrador' ? 'Gerenciar produtos' : 'Meus produtos' }}
                </a>
                <span>/</span>
                <span>{{ $editando ? 'Editar' : 'Novo' }}</span>
            </div>

            @if ($errors->any())
                <div class="front-alert-error" role="alert">
                    @foreach ($errors->all() as $erro)
                        <p>{{ $erro }}</p>
                    @endforeach
                </div>
            @endif

            <form
                action="{{ $editando ? route('produtos.update', $produto) : route('produtos.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="front-form-card"
            >
                @csrf

                @if ($editando)
                    @method('PUT')
                @endif

                <div class="front-form-section-heading">
                    <span>{{ $editando ? 'E' : '+' }}</span>

                    <div>
                        <h1>{{ $editando ? 'Editar produto' : 'Cadastrar produto' }}</h1>
                        <p>Preencha os dados do anúncio e confira as informações antes de salvar.</p>
                    </div>
                </div>

                <div class="front-form-grid">
                    <label class="front-field front-field-wide">
                        <span>Nome</span>
                        <input
                            name="nome"
                            type="text"
                            maxlength="150"
                            value="{{ old('nome', $editando ? $produto->nome : '') }}"
                            required
                            autofocus
                        >
                    </label>

                    <label class="front-field">
                        <span>Categoria</span>

                        <select name="categoria_id" required>
                            <option value="">Selecione uma categoria</option>

                            @foreach ($categorias as $categoria)
                                <option
                                    value="{{ $categoria->id }}"
                                    @selected(
                                        (string) old(
                                            'categoria_id',
                                            $editando ? $produto->categoria_id : ''
                                        ) === (string) $categoria->id
                                    )
                                >
                                    {{ $categoria->nome }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <label class="front-field">
                        <span>Preço</span>
                        <input
                            name="preco"
                            type="text"
                            inputmode="decimal"
                            value="{{ old(
                                'preco',
                                $editando
                                    ? number_format((float) $produto->preco, 2, ',', '.')
                                    : ''
                            ) }}"
                            placeholder="0,00"
                            required
                        >
                    </label>

                    <label class="front-field">
                        <span>Quantidade</span>
                        <input
                            name="quantidade"
                            type="number"
                            min="0"
                            step="1"
                            value="{{ old('quantidade', $editando ? $produto->quantidade : 1) }}"
                            required
                        >
                    </label>

                    <label class="front-field front-field-wide">
                        <span>{{ $editando ? 'Nova foto (opcional)' : 'Foto' }}</span>
                        <input
                            name="foto"
                            type="file"
                            accept="image/jpeg,image/png,image/webp"
                            @required(! $editando)
                        >
                        <small>Formatos permitidos: JPEG, PNG ou WEBP. Tamanho máximo: 5 MB.</small>
                    </label>

                    @if ($editando && $produto->foto)
                        <div class="front-field">
                            <span>Foto atual</span>
                            <img
                                src="{{ asset($produto->foto) }}"
                                alt="Foto atual de {{ $produto->nome }}"
                                class="h-24 w-32 rounded-lg border border-slate-200 object-cover"
                            >
                        </div>
                    @endif

                    <label class="front-field front-field-full">
                        <span>Descrição</span>
                        <textarea
                            name="descricao"
                            rows="6"
                            maxlength="10000"
                            required
                        >{{ old('descricao', $editando ? $produto->descricao : '') }}</textarea>
                    </label>
                </div>

                <div class="front-form-actions">
                    <a href="{{ route('produtos.manage') }}" class="front-button front-button-ghost">
                        Cancelar
                    </a>

                    <button type="submit" class="front-button front-button-primary">
                        {{ $editando ? 'Salvar alterações' : 'Cadastrar produto' }}
                    </button>
                </div>
            </form>
        </div>
    </main>

    <x-footer />
</x-app-layout>
