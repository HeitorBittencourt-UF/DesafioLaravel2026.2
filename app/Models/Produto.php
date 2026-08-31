<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'Produtos';

    protected $fillable = [
        'nome',
        'descricao',
        'foto',
        'preco',
        'quantidade',
        'UsuarioId',
        'categoria_id',
    ];

    protected function casts(): array
    {
        return [
            'preco' => 'decimal:2',
            'quantidade' => 'integer',
        ];
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'UsuarioId');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function fotos()
    {
        return $this->hasMany(ProdutoFoto::class, 'ProdutoId');
    }

    public function itensCarrinho()
    {
        return $this->hasMany(ItemCarrinho::class, 'ProdutoId');
    }

    public function itensVendas()
    {
        return $this->hasMany(ItemVenda::class, 'ProdutoId');
    }
}