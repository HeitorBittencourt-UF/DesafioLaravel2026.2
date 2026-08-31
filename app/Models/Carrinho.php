<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrinho extends Model
{
    use HasFactory;

    protected $table = 'Carrinhos';

    protected $fillable = [
        'UsuarioId',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'UsuarioId');
    }

    public function itens()
    {
        return $this->hasMany(ItemCarrinho::class, 'CarrinhoId');
    }
}