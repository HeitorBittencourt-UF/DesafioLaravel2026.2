<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Tabela associada ao Model.
     *
     * @var string
     */
    protected $table = 'Usuarios';
    protected $primaryKey = 'id';
    /**
     * Os atributos que podem ser preenchidos em massa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nome',
        'email',
        'senha',
        'tipo',
        'cpf',
        'data_nascimento',
        'telefone',
        'saldo',
        'foto',
        'criador_id',
    ];

    /**
     * Os atributos ocultos em retornos JSON.
     *
     * @var list<string>
     */
    protected $hidden = [
        'senha',
    ];

    /**
     * Indica ao Laravel que a coluna da senha se chama 'senha' e não 'password'.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->senha;
    }

    /**
     * Converte os tipos de dados automaticamente ao acessar as propriedades.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'data_nascimento' => 'date',
            'saldo' => 'decimal:2',
            'senha' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relacionamentos Eloquent
    |--------------------------------------------------------------------------
    */

    /**
     * Auto-relacionamento: O usuário admin/criador que cadastrou este usuário.
     */
    public function criador()
    {
        return $this->belongsTo(Usuario::class, 'criador_id');
    }

    /**
     * Auto-relacionamento: Usuários cadastrados por este usuário.
     */
    public function usuariosCriados()
    {
        return $this->hasMany(Usuario::class, 'criador_id');
    }

    /**
     * Muitos para Muitos: Endereços vinculados a este usuário (via tabela pivot Usuarios_Enderecos).
     */
    public function enderecos()
    {
        return $this->belongsToMany(Endereco::class, 'Usuarios_Enderecos', 'UsuarioId', 'EnderecoId');
    }

    /**
     * Um para Muitos: Produtos cadastrados/anunciados por este usuário.
     */
    public function produtos()
    {
        return $this->hasMany(Produto::class, 'UsuarioId');
    }

    /**
     * Um para Um: Carrinho de compras do usuário.
     */
    public function carrinho()
    {
        return $this->hasOne(Carrinho::class, 'UsuarioId');
    }

    /**
     * Um para Muitos: Cartões cadastrados pelo usuário.
     */
    public function cartoes()
    {
        return $this->hasMany(Cartao::class, 'UsuarioId');
    }

    /**
     * Um para Muitos: Histórico de compras realizadas pelo usuário (como comprador).
     */
    public function compras()
    {
        return $this->hasMany(Venda::class, 'CompradorId');
    }

    /**
     * Um para Muitos: Itens de produtos vendidos por este usuário (como vendedor).
     */
    public function vendas()
    {
        return $this->hasMany(ItemVenda::class, 'VendedorId');
    }
}