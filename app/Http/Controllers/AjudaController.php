<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AjudaController extends Controller
{
    public function index(): View
    {
        return view('ajuda');
    }

    public function send(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255'],
            'mensagem' => ['required', 'string', 'max:5000'],
        ]);

        Mail::raw($dados['mensagem'], function (Message $message) use ($dados): void {
            $message
                ->to(config('mail.from.address'))
                ->replyTo($dados['email'], $dados['nome'])
                ->subject('Contato pela Central de Ajuda');
        });

        return redirect()
            ->to(route('ajuda').'#contato')
            ->with('success', 'Mensagem enviada com sucesso.');
    }
}
