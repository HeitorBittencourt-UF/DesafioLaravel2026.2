<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCarrinho extends Model
{
    use HasFactory;

    protected $table = 'ItensCarrinho';

    protected $fillable = [
        'CarrinhoId',
        'ProdutoId',
        'quantidade',
    ];

    protected function casts(): array
    {
        return [
            'quantidade' => 'integer',
        ];
    }

    public function carrinho()
    {
        return $this->belongsTo(Carrinho::class, 'CarrinhoId');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'ProdutoId');
    }
}