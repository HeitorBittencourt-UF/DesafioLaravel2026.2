<?php

namespace App\Http\Controllers;

use App\Models\Usuario;

class UsuarioController extends Controller
{
    public function usuarios()
    {
        $pessoas = Usuario::with('enderecos')->where('tipo', 'usuario')->get();

        $total = $pessoas->count();
        $ativos = $total;
        $novosEsteMes = $pessoas->filter(function ($pessoa) {
            return $pessoa->created_at && $pessoa->created_at->isCurrentMonth();
        })->count();

        return view('usuario-management', [
            'pessoas' => $pessoas,
            'administradores' => false,
            'total' => $total,
            'ativos' => $ativos,
            'novosEsteMes' => $novosEsteMes,
            'createRoute' => 'admin.users.create',
            'showRoute' => 'admin.users.show',
            'editRoute' => 'admin.users.edit',
        ]);
    }

    public function administradores()
    {
        $pessoas = Usuario::with('enderecos')->where('tipo', 'administrador')->get();

        $total = $pessoas->count();
        $ativos = $total;
        $novosEsteMes = $pessoas->filter(function ($pessoa) {
            return $pessoa->created_at && $pessoa->created_at->isCurrentMonth();
        })->count();

        return view('usuario-management', [
            'pessoas' => $pessoas,
            'administradores' => true,
            'total' => $total,
            'ativos' => $ativos,
            'novosEsteMes' => $novosEsteMes,
            'createRoute' => 'admin.admins.create',
            'showRoute' => 'admin.admins.show',
            'editRoute' => 'admin.admins.edit',
        ]);
    }
}