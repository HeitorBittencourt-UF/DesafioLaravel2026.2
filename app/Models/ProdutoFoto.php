<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoFoto extends Model
{
    use HasFactory;

    protected $table = 'Produtos_Fotos';

    protected $fillable = [
        'ProdutoId',
        'foto',
        'principal',
    ];

    protected function casts(): array
    {
        return [
            'principal' => 'boolean',
        ];
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'ProdutoId');
    }
}