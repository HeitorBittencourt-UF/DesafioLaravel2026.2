<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        'cpf' => preg_replace('/\D/', '', (string) $request->cpf),
        ]);

        $telefoneOriginal = trim((string) $request->telefone);

        $telefoneNumeros = preg_replace(
            '/\D/',
            '',
            $telefoneOriginal
        );

        if (str_starts_with($telefoneOriginal, '+')) {

    // O usuário informou explicitamente
    // um código internacional.
    //
    // Exemplo:
    // +12025550123
    //
    // Apenas removemos espaços, parênteses e hífens.
    $telefoneNormalizado = '+' . $telefoneNumeros;

    } elseif (
        str_starts_with($telefoneNumeros, '55') &&
        in_array(strlen($telefoneNumeros), [12, 13])
    ) {

        // Exemplo:
        // 5532999999999
        //
        // Já possui o código 55,
        // faltava somente o +.
        $telefoneNormalizado = '+' . $telefoneNumeros;

        } elseif (
            in_array(strlen($telefoneNumeros), [10, 11])
        ) {

            // Exemplo:
            // 32999999999
            //
            // Possui DDD + telefone,
            // mas não possui código do país.
            //
            // Assumimos Brasil.
            $telefoneNormalizado = '+55' . $telefoneNumeros;

        } else {

            /*
            * Não tentamos inventar informações.
            *
            * Exemplo:
            * 999999999
            *
            * Não sabemos qual é o DDD.
            * Deixamos a validação rejeitar.
            */
            $telefoneNormalizado = $telefoneOriginal;
        }

        $request->merge([
            'telefone' => $telefoneNormalizado,
        ]);


            

        $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', 'size:11', 'unique:' . Usuario::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Usuario::class],
            'telefone' => ['required','string',

            function ($attribute, $value, $fail) {

                /*
                * Formato internacional:
                *
                * precisa começar com +
                * e ter de 8 a 15 dígitos.
                */
                if (!preg_match('/^\+[1-9]\d{7,14}$/', $value)) {
                    $fail('Informe um telefone válido com DDD.');
                    return;
                }

                /*
                * Caso seja brasileiro (+55),
                * depois do 55 precisamos ter:
                *
                * 10 dígitos → DDD + telefone fixo
                * 11 dígitos → DDD + celular
                */
                if (
                    str_starts_with($value, '+55') &&
                    !preg_match('/^\+55\d{10,11}$/', $value)
                ) {
                    $fail('Informe um telefone brasileiro válido com DDD.');
                }
            },
        ],
            'data_nascimento' => ['required', 'date'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = Usuario::create([
            'nome' => $request->nome,
            'cpf' => $request->cpf,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'data_nascimento' => $request->data_nascimento,
            'senha' => $request->password,
            'tipo' => 'usuario',
            'saldo' => 0.00,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
