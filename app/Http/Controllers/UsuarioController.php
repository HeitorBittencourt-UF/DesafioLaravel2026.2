<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class UsuarioController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTAGENS
    |--------------------------------------------------------------------------
    */

    public function usuarios(Request $request): View
    {
        $this->garantirAdministrador($request);

        $pessoas = Usuario::with('enderecos')
            ->where('tipo', 'usuario')
            ->latest()
            ->get();

        return view('usuario-management', [
            'pessoas' => $pessoas,
            'administradores' => false,
            'total' => $pessoas->count(),
            'ativos' => $pessoas->count(),
            'novosEsteMes' => $pessoas
                ->filter(fn ($pessoa) =>
                    $pessoa->created_at &&
                    $pessoa->created_at->isCurrentMonth()
                )
                ->count(),

            'createRoute' => 'admin.users.create',
            'showRoute' => 'admin.users.show',
            'editRoute' => 'admin.users.edit',
            'destroyRoute' => 'admin.users.destroy',
        ]);
    }

    public function administradores(Request $request): View
    {
        $this->garantirAdministrador($request);

        $pessoas = Usuario::with('enderecos')
            ->where('tipo', 'administrador')
            ->latest()
            ->get();

        return view('usuario-management', [
            'pessoas' => $pessoas,
            'administradores' => true,
            'total' => $pessoas->count(),
            'ativos' => $pessoas->count(),
            'novosEsteMes' => $pessoas
                ->filter(fn ($pessoa) =>
                    $pessoa->created_at &&
                    $pessoa->created_at->isCurrentMonth()
                )
                ->count(),

            'createRoute' => 'admin.admins.create',
            'showRoute' => 'admin.admins.show',
            'editRoute' => 'admin.admins.edit',
            'destroyRoute' => 'admin.admins.destroy',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | USUÁRIOS
    |--------------------------------------------------------------------------
    */

    public function createUsuario(Request $request): View
    {
        $this->garantirAdministrador($request);

        return $this->formulario(
            pessoa: null,
            administrador: false,
            modo: 'criar'
        );
    }

    public function storeUsuario(Request $request): RedirectResponse
    {
        $this->garantirAdministrador($request);

        $this->normalizarDados($request);

        $dados = $this->validarPessoa(
            request: $request,
            pessoa: null,
            administrador: false,
            criando: true
        );

        $this->criarPessoa(
            request: $request,
            dados: $dados,
            tipo: 'usuario'
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuário criado com sucesso.');
    }

    public function showUsuario(
        Request $request,
        Usuario $usuario
    ): View {
        $this->garantirAdministrador($request);
        $this->garantirTipo($usuario, 'usuario');

        $usuario->load('enderecos');

        return $this->formulario(
            pessoa: $usuario,
            administrador: false,
            modo: 'visualizar'
        );
    }

    public function editUsuario(
        Request $request,
        Usuario $usuario
    ): View {
        $this->garantirAdministrador($request);
        $this->garantirTipo($usuario, 'usuario');

        $usuario->load('enderecos');

        return $this->formulario(
            pessoa: $usuario,
            administrador: false,
            modo: 'editar'
        );
    }

    public function updateUsuario(
        Request $request,
        Usuario $usuario
    ): RedirectResponse {
        $this->garantirAdministrador($request);
        $this->garantirTipo($usuario, 'usuario');

        $this->normalizarDados($request);

        $dados = $this->validarPessoa(
            request: $request,
            pessoa: $usuario,
            administrador: false,
            criando: false
        );

        $this->atualizarPessoa(
            request: $request,
            pessoa: $usuario,
            dados: $dados
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroyUsuario(
        Request $request,
        Usuario $usuario
    ): RedirectResponse {
        $this->garantirAdministrador($request);
        $this->garantirTipo($usuario, 'usuario');

        $usuario->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuário excluído com sucesso.');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMINISTRADORES
    |--------------------------------------------------------------------------
    */

    public function createAdministrador(Request $request): View
    {
        $this->garantirAdministrador($request);

        return $this->formulario(
            pessoa: null,
            administrador: true,
            modo: 'criar'
        );
    }

    public function storeAdministrador(
        Request $request
    ): RedirectResponse {
        $adminLogado = $this->garantirAdministrador($request);

        $this->normalizarDados($request);

        $dados = $this->validarPessoa(
            request: $request,
            pessoa: null,
            administrador: true,
            criando: true
        );

        $this->criarPessoa(
            request: $request,
            dados: $dados,
            tipo: 'administrador',
            criadorId: $adminLogado->getKey()
        );

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Administrador criado com sucesso.');
    }

    public function showAdministrador(
        Request $request,
        Usuario $administrador
    ): View {
        $this->garantirAdministrador($request);
        $this->garantirTipo($administrador, 'administrador');

        $administrador->load('enderecos');

        return $this->formulario(
            pessoa: $administrador,
            administrador: true,
            modo: 'visualizar'
        );
    }

    public function editAdministrador(
        Request $request,
        Usuario $administrador
    ): View {
        $adminLogado = $this->garantirAdministrador($request);

        $this->garantirTipo(
            $administrador,
            'administrador'
        );

        $this->garantirGerenciamentoAdministrador(
            $adminLogado,
            $administrador
        );

        $administrador->load('enderecos');

        return $this->formulario(
            pessoa: $administrador,
            administrador: true,
            modo: 'editar'
        );
    }

    public function updateAdministrador(
        Request $request,
        Usuario $administrador
    ): RedirectResponse {
        $adminLogado = $this->garantirAdministrador($request);

        $this->garantirTipo(
            $administrador,
            'administrador'
        );

        $this->garantirGerenciamentoAdministrador(
            $adminLogado,
            $administrador
        );

        $this->normalizarDados($request);

        $dados = $this->validarPessoa(
            request: $request,
            pessoa: $administrador,
            administrador: true,
            criando: false
        );

        $this->atualizarPessoa(
            request: $request,
            pessoa: $administrador,
            dados: $dados
        );

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Administrador atualizado com sucesso.');
    }

    public function destroyAdministrador(
        Request $request,
        Usuario $administrador
    ): RedirectResponse {
        $adminLogado = $this->garantirAdministrador($request);

        $this->garantirTipo(
            $administrador,
            'administrador'
        );

        $this->garantirGerenciamentoAdministrador(
            $adminLogado,
            $administrador
        );

        $excluindoPropriaConta =
            (int) $adminLogado->getKey() ===
            (int) $administrador->getKey();

        $administrador->delete();

        if ($excluindoPropriaConta) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('success', 'Sua conta foi excluída.');
        }

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Administrador excluído com sucesso.');
    }

    /*
    |--------------------------------------------------------------------------
    | FUNÇÕES AUXILIARES
    |--------------------------------------------------------------------------
    */

    private function formulario(
        ?Usuario $pessoa,
        bool $administrador,
        string $modo
    ): View {
        $criando = $modo === 'criar';
        $visualizando = $modo === 'visualizar';

        $prefixo = $administrador
            ? 'admin.admins'
            : 'admin.users';

        return view('usuario-form', [
            'pessoa' => $pessoa,
            'endereco' => $pessoa?->enderecos->first(),

            'administrador' => $administrador,
            'modo' => $modo,
            'criando' => $criando,
            'visualizando' => $visualizando,

            'indexRoute' => $prefixo . '.index',
            'storeRoute' => $prefixo . '.store',
            'updateRoute' => $prefixo . '.update',
            'editRoute' => $prefixo . '.edit',

            'id' => $pessoa?->getKey(),
        ]);
    }

    private function garantirAdministrador(
        Request $request
    ): Usuario {
        $usuario = $request->user();

        abort_unless(
            $usuario instanceof Usuario,
            401
        );

        abort_unless(
            $usuario->tipo === 'administrador',
            403,
            'Apenas administradores podem acessar esta área.'
        );

        return $usuario;
    }

    private function garantirTipo(
        Usuario $usuario,
        string $tipo
    ): void {
        abort_unless(
            $usuario->tipo === $tipo,
            404
        );
    }

    private function garantirGerenciamentoAdministrador(
        Usuario $adminLogado,
        Usuario $administradorAlvo
    ): void {
        $ehProprioPerfil =
            (int) $adminLogado->getKey() ===
            (int) $administradorAlvo->getKey();

        $foiCriadoPeloAdmin =
            (int) $administradorAlvo->criador_id ===
            (int) $adminLogado->getKey();

        abort_unless(
            $ehProprioPerfil || $foiCriadoPeloAdmin,
            403,
            'Você só pode editar ou excluir seu próprio perfil ou administradores criados por você.'
        );
    }

    private function normalizarDados(Request $request): void
    {
        $request->merge([
            'cpf' => preg_replace(
                '/\D/',
                '',
                (string) $request->input('cpf')
            ),

            'cep' => preg_replace(
                '/\D/',
                '',
                (string) $request->input('cep')
            ),

            'estado' => strtoupper(
                trim((string) $request->input('estado'))
            ),
        ]);
    }

    private function validarPessoa(
        Request $request,
        ?Usuario $pessoa,
        bool $administrador,
        bool $criando
    ): array {
        $senha = $criando
            ? ['required', 'string', 'min:8']
            : ['nullable', 'string', 'min:8'];

        $foto = $administrador && $criando
            ? ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']
            : ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'];

        $regras = [
            'nome' => [
                'required',
                'string',
                'max:150',
            ],

            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('Usuarios', 'email')
                    ->ignore($pessoa?->getKey()),
            ],

            'senha' => $senha,

            'cpf' => [
                'required',
                'digits:11',
                Rule::unique('Usuarios', 'cpf')
                    ->ignore($pessoa?->getKey()),
            ],

            'telefone' => [
                'required',
                'string',
                'max:20',
            ],

            'data_nascimento' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            'foto' => $foto,

            'cep' => [
                'required',
                'digits:8',
            ],

            'logradouro' => [
                'required',
                'string',
                'max:150',
            ],

            'numero' => [
                'required',
                'string',
                'max:10',
            ],

            'bairro' => [
                'required',
                'string',
                'max:100',
            ],

            'cidade' => [
                'required',
                'string',
                'max:100',
            ],

            'estado' => [
                'required',
                'string',
                'size:2',
            ],

            'complemento' => [
                'nullable',
                'string',
                'max:100',
            ],
        ];

        if (! $administrador) {
            $regras['saldo'] = [
                'nullable',
                'numeric',
                'min:0',
                'max:99999999.99',
            ];
        }

        return $request->validate($regras);
    }

    private function criarPessoa(
        Request $request,
        array $dados,
        string $tipo,
        ?int $criadorId = null
    ): Usuario {
        $caminhoFoto = null;

        if ($request->hasFile('foto')) {
            $caminhoFoto = $request
                ->file('foto')
                ->store('usuarios', 'public');
        }

        try {
            return DB::transaction(function () use (
                $dados,
                $tipo,
                $criadorId,
                $caminhoFoto
            ) {
                $usuario = Usuario::create([
                    'nome' => $dados['nome'],
                    'email' => $dados['email'],
                    'senha' => $dados['senha'],
                    'tipo' => $tipo,
                    'cpf' => $dados['cpf'],
                    'data_nascimento' => $dados['data_nascimento'],
                    'telefone' => $dados['telefone'],
                    'saldo' => $tipo === 'usuario'
                        ? ($dados['saldo'] ?? 0)
                        : 0,
                    'foto' => $caminhoFoto
                        ? 'storage/' . $caminhoFoto
                        : null,
                    'criador_id' => $criadorId,
                ]);

                $endereco = Endereco::create([
                    'cep' => $dados['cep'],
                    'logradouro' => $dados['logradouro'],
                    'numero' => $dados['numero'],
                    'complemento' => $dados['complemento'] ?? null,
                    'bairro' => $dados['bairro'],
                    'cidade' => $dados['cidade'],
                    'estado' => $dados['estado'],
                ]);

                $usuario
                    ->enderecos()
                    ->attach($endereco->getKey());

                return $usuario;
            });
        } catch (Throwable $erro) {
            if ($caminhoFoto) {
                Storage::disk('public')
                    ->delete($caminhoFoto);
            }

            throw $erro;
        }
    }

    private function atualizarPessoa(
        Request $request,
        Usuario $pessoa,
        array $dados
    ): void {
        $caminhoFotoNova = null;

        if ($request->hasFile('foto')) {
            $caminhoFotoNova = $request
                ->file('foto')
                ->store('usuarios', 'public');
        }

        $fotoAntiga = $pessoa->foto;

        try {
            DB::transaction(function () use (
                $pessoa,
                $dados,
                $caminhoFotoNova
            ) {
                $dadosUsuario = [
                    'nome' => $dados['nome'],
                    'email' => $dados['email'],
                    'cpf' => $dados['cpf'],
                    'data_nascimento' => $dados['data_nascimento'],
                    'telefone' => $dados['telefone'],
                ];

                if ($pessoa->tipo === 'usuario') {
                    $dadosUsuario['saldo'] =
                        $dados['saldo'] ?? $pessoa->saldo;
                }

                if (! empty($dados['senha'])) {
                    $dadosUsuario['senha'] =
                        $dados['senha'];
                }

                if ($caminhoFotoNova) {
                    $dadosUsuario['foto'] =
                        'storage/' . $caminhoFotoNova;
                }

                $pessoa->update($dadosUsuario);

                $endereco = $pessoa
                    ->enderecos()
                    ->first();

                $dadosEndereco = [
                    'cep' => $dados['cep'],
                    'logradouro' => $dados['logradouro'],
                    'numero' => $dados['numero'],
                    'complemento' => $dados['complemento'] ?? null,
                    'bairro' => $dados['bairro'],
                    'cidade' => $dados['cidade'],
                    'estado' => $dados['estado'],
                ];

                if ($endereco) {
                    $endereco->update($dadosEndereco);
                } else {
                    $novoEndereco =
                        Endereco::create($dadosEndereco);

                    $pessoa
                        ->enderecos()
                        ->attach($novoEndereco->getKey());
                }
            });
        } catch (Throwable $erro) {
            if ($caminhoFotoNova) {
                Storage::disk('public')
                    ->delete($caminhoFotoNova);
            }

            throw $erro;
        }

        if ($caminhoFotoNova) {
            $this->excluirFotoLocal($fotoAntiga);
        }
    }

    private function excluirFotoLocal(?string $foto): void
    {
        $caminho = ltrim(
            trim((string) $foto),
            '/'
        );

        if (! str_starts_with($caminho, 'storage/')) {
            return;
        }

        $caminho = substr(
            $caminho,
            strlen('storage/')
        );

        if ($caminho !== '') {
            Storage::disk('public')
                ->delete($caminho);
        }
    }
}