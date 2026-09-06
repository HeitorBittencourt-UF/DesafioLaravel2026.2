<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'nome' => trim((string) $request->nome),
            'cpf' => preg_replace('/\D/', '', (string) $request->cpf),
            'email' => Str::lower(trim((string) $request->email)),
            'telefone' => $this->normalizarTelefone((string) $request->telefone),
        ]);

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:150'],
            'cpf' => [
                'required',
                'string',
                'size:11',
                'unique:' . Usuario::class,
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! $this->cpfValido((string) $value)) {
                        $fail('Informe um CPF válido.');
                    }
                },
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:150',
                'unique:' . Usuario::class,
            ],
            'telefone' => [
                'required',
                'string',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $telefone = (string) $value;

                    if (! preg_match('/^\+[1-9]\d{7,14}$/', $telefone)) {
                        $fail('Informe um telefone válido com código do país e DDD.');

                        return;
                    }

                    if (
                        str_starts_with($telefone, '+55') &&
                        ! preg_match('/^\+55\d{10,11}$/', $telefone)
                    ) {
                        $fail('Informe um telefone brasileiro válido com DDD.');
                    }
                },
            ],
            'data_nascimento' => ['required', 'date', 'before_or_equal:today'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = Usuario::create([
            'nome' => $dados['nome'],
            'cpf' => $dados['cpf'],
            'email' => $dados['email'],
            'telefone' => $dados['telefone'],
            'data_nascimento' => $dados['data_nascimento'],
            'senha' => $dados['password'],
            'tipo' => 'usuario',
            'saldo' => 0.00,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    private function normalizarTelefone(string $telefone): string
    {
        $telefoneOriginal = trim($telefone);
        $numeros = preg_replace('/\D/', '', $telefoneOriginal) ?? '';

        if (str_starts_with($telefoneOriginal, '+')) {
            return '+' . $numeros;
        }

        if (
            str_starts_with($numeros, '55') &&
            in_array(strlen($numeros), [12, 13], true)
        ) {
            return '+' . $numeros;
        }

        if (in_array(strlen($numeros), [10, 11], true)) {
            return '+55' . $numeros;
        }

        return $telefoneOriginal;
    }

    private function cpfValido(string $cpf): bool
    {
        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($posicao = 9; $posicao < 11; $posicao++) {
            $soma = 0;

            for ($indice = 0; $indice < $posicao; $indice++) {
                $soma += (int) $cpf[$indice] * (($posicao + 1) - $indice);
            }

            $digito = ((10 * $soma) % 11) % 10;

            if ((int) $cpf[$posicao] !== $digito) {
                return false;
            }
        }

        return true;
    }
}
