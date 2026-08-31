<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemVenda extends Model
{
    use HasFactory;

    protected $table = 'ItensVendas';

    protected $fillable = [
        'VendasId',
        'ProdutoId',
        'VendedorId',
        'quantidade',
        'ValorUnitario',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'quantidade' => 'integer',
            'ValorUnitario' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    public function venda()
    {
        return $this->belongsTo(Venda::class, 'VendasId');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'ProdutoId');
    }

    public function vendedor()
    {
        return $this->belongsTo(Usuario::class, 'VendedorId');
    }
}