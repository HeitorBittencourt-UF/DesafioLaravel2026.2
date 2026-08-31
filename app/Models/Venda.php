<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;

    protected $table = 'Vendas';

    protected $fillable = [
        'CompradorId',
        'ValorTotal',
        'StatusPagamento',
        'LocalPagamento',
        'codigo_transacao',
    ];

    protected function casts(): array
    {
        return [
            'ValorTotal' => 'decimal:2',
        ];
    }

    public function comprador()
    {
        return $this->belongsTo(Usuario::class, 'CompradorId');
    }

    public function itens()
    {
        return $this->hasMany(ItemVenda::class, 'VendasId');
    }
}