<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompraController extends Controller
{
    public function show(Request $request, Produto $produto): View
    {
        $usuario = $request->user();

        abort_unless($usuario instanceof Usuario, 401);

        abort_if(
            $usuario->tipo === 'administrador',
            403,
            'Administradores não podem realizar compras.'
        );

        abort_if(
            (int) $produto->UsuarioId === (int) $usuario->getKey(),
            403,
            'Você não pode comprar um produto anunciado por você.'
        );

        abort_if($produto->quantidade < 1, 404, 'Este produto não está disponível.');

        $produto->load(['categoria', 'usuario', 'fotos']);

        $cepConsultado = null;
        $cepInformado = preg_replace('/\D/', '', (string) $request->query('cep', ''));

        if ($cepInformado !== '') {
            $request->validate([
                'cep' => ['required', 'regex:/^\d{5}-?\d{3}$/'],
            ], [
                'cep.regex' => 'Informe um CEP válido com 8 números.',
            ]);

            $cepConsultado = substr($cepInformado, 0, 5).'-'.substr($cepInformado, 5, 3);
        }

        return view('compra', compact('produto', 'cepConsultado'));
    }
}
