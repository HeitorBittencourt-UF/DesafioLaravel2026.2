<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cartao extends Model
{
    use HasFactory;

    protected $table = 'Cartoes';

    protected $fillable = [
        'UsuarioId',
        'token',
        'bandeira',
        'ultimos_digitos',
        'mes_expiracao',
        'ano_expiracao',
        'principal',
    ];

    protected function casts(): array
    {
        return [
            'principal' => 'boolean',
            'mes_expiracao' => 'integer',
            'ano_expiracao' => 'integer',
        ];
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'UsuarioId');
    }
}