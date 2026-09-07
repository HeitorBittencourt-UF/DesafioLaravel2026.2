<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class AdminEmailController extends Controller
{
    public function create(Request $request): View
    {
        $this->garantirAdministrador($request);

        $usuarios = Usuario::query()
            ->where('tipo', 'usuario')
            ->orderBy('nome')
            ->get(['id', 'nome', 'email']);

        return view('email', compact('usuarios'));
    }

    public function send(Request $request): RedirectResponse
    {
        $this->garantirAdministrador($request);

        $dados = $request->validate([
            'usuario' => [
                'required',
                Rule::exists('Usuarios', 'id')->where(fn ($query) => $query->where('tipo', 'usuario')),
            ],
            'assunto' => ['required', 'string', 'max:150'],
            'conteudo' => ['required', 'string', 'max:5000'],
        ]);

        $destinatario = Usuario::findOrFail($dados['usuario']);

        try {
            Mail::raw($dados['conteudo'], function (Message $message) use ($destinatario, $dados): void {
                $message
                    ->to($destinatario->email, $destinatario->nome)
                    ->subject($dados['assunto']);
            });
        } catch (TransportExceptionInterface $erro) {
            report($erro);

            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'Não foi possível enviar o e-mail. Verifique a configuração SMTP e tente novamente.',
                ]);
        }

        return redirect()
            ->route('admin.email')
            ->with('success', "E-mail enviado com sucesso para {$destinatario->email}.");
    }

    private function garantirAdministrador(Request $request): void
    {
        $usuario = $request->user();

        abort_unless(
            $usuario instanceof Usuario && $usuario->tipo === 'administrador',
            403
        );
    }
}