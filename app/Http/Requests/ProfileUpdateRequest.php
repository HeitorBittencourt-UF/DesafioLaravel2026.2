<?php

namespace App\Http\Requests;

use App\Models\Usuario;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $telefoneOriginal = trim((string) $this->telefone);
        $telefoneNumeros = preg_replace('/\D/', '', $telefoneOriginal);

        if (str_starts_with($telefoneOriginal, '+')) {
            $telefoneNormalizado = '+' . $telefoneNumeros;
        } elseif (str_starts_with($telefoneNumeros, '55') && in_array(strlen($telefoneNumeros), [12, 13], true)) {
            $telefoneNormalizado = '+' . $telefoneNumeros;
        } elseif (in_array(strlen($telefoneNumeros), [10, 11], true)) {
            $telefoneNormalizado = '+55' . $telefoneNumeros;
        } else {
            $telefoneNormalizado = $telefoneOriginal;
        }

        $this->merge([
            'telefone' => $telefoneNormalizado,
        ]);
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:150'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(Usuario::class)->ignore($this->user()->id),
            ],
            'telefone' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (! preg_match('/^\+[1-9]\d{7,14}$/', $value)) {
                        $fail('Informe um telefone válido com DDD.');
                        return;
                    }

                    if (str_starts_with($value, '+55') && ! preg_match('/^\+55\d{10,11}$/', $value)) {
                        $fail('Informe um telefone brasileiro válido com DDD.');
                    }
                },
            ],
        ];
    }
}