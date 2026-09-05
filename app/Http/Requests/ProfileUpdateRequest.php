<?php

namespace App\Http\Requests;

use App\Models\Usuario;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Prepara os dados antes da validação.
     */
    protected function prepareForValidation(): void
    {
        $telefoneOriginal = trim((string) $this->telefone);

        // Remove tudo que não for número.
        //
        // Exemplo:
        // (32) 99999-9999
        //
        // vira:
        // 32999999999
        $telefoneNumeros = preg_replace(
            '/\D/',
            '',
            $telefoneOriginal
        );


        /*
        |--------------------------------------------------------------------------
        | NORMALIZA O TELEFONE
        |--------------------------------------------------------------------------
        */

        if (str_starts_with($telefoneOriginal, '+')) {

            // Exemplo:
            // +55 (32) 99999-9999
            //
            // vira:
            // +5532999999999
            $telefoneNormalizado = '+' . $telefoneNumeros;

        } elseif (
            str_starts_with($telefoneNumeros, '55') &&
            in_array(strlen($telefoneNumeros), [12, 13])
        ) {

            // Exemplo:
            // 5532999999999
            //
            // vira:
            // +5532999999999
            $telefoneNormalizado = '+' . $telefoneNumeros;

        } elseif (
            in_array(strlen($telefoneNumeros), [10, 11])
        ) {

            // Exemplo:
            // 32999999999
            //
            // vira:
            // +5532999999999
            $telefoneNormalizado = '+55' . $telefoneNumeros;

        } else {

            // Se o telefone estiver incorreto,
            // deixa a validação abaixo rejeitar.
            $telefoneNormalizado = $telefoneOriginal;
        }


        $this->merge([
            'telefone' => $telefoneNormalizado,
        ]);
    }


    /**
     * Regras de validação para atualização do perfil.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => [
                'required',
                'string',
                'max:150',
            ],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',

                // Verifica se o e-mail é único.
                //
                // O ignore permite que o usuário
                // continue usando o próprio e-mail.
                Rule::unique(Usuario::class)
                    ->ignore($this->user()->id),
            ],

            'telefone' => [
                'required',
                'string',

                function ($attribute, $value, $fail) {

                    /*
                     * Formato internacional:
                     *
                     * deve começar com +
                     * e possuir entre 8 e 15 dígitos.
                     */
                    if (!preg_match('/^\+[1-9]\d{7,14}$/', $value)) {

                        $fail(
                            'Informe um telefone válido com DDD.'
                        );

                        return;
                    }


                    /*
                     * Se for brasileiro (+55),
                     * deve possuir:
                     *
                     * +55 + 10 dígitos
                     *
                     * ou
                     *
                     * +55 + 11 dígitos
                     */
                    if (
                        str_starts_with($value, '+55') &&
                        !preg_match('/^\+55\d{10,11}$/', $value)
                    ) {

                        $fail(
                            'Informe um telefone brasileiro válido com DDD.'
                        );
                    }
                },
            ],
        ];
    }
}