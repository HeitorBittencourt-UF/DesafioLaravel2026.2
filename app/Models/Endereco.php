<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use HasFactory;

    protected $table = 'Enderecos';

    protected $fillable = [
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
    ];

    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'Usuarios_Enderecos', 'EnderecoId', 'UsuarioId');
    }
}