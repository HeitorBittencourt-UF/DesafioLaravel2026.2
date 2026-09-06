<?php

namespace App\Http\Requests;

use App\Models\Usuario;
use Illuminate\Foundation\Http\FormRequest;

class ProdutoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() instanceof Usuario;
    }

    protected function prepareForValidation(): void
    {
        $preco = trim((string) $this->input('preco', ''));

        if (str_contains($preco, ',')) {
            $preco = str_replace('.', '', $preco);
            $preco = str_replace(',', '.', $preco);
        }

        $this->merge([
            'preco' => $preco,
        ]);
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:150'],
            'descricao' => ['required', 'string', 'max:10000'],
            'categoria_id' => ['required', 'integer', 'exists:Categorias,id'],
            'preco' => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'quantidade' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'foto' => [
                $this->isMethod('post') ? 'required' : 'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:5120',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'Informe o nome do produto.',
            'nome.max' => 'O nome pode ter no máximo 150 caracteres.',
            'descricao.required' => 'Informe a descrição do produto.',
            'descricao.max' => 'A descrição pode ter no máximo 10.000 caracteres.',
            'categoria_id.required' => 'Selecione uma categoria.',
            'categoria_id.exists' => 'A categoria selecionada não existe.',
            'preco.required' => 'Informe o preço do produto.',
            'preco.numeric' => 'Informe um preço válido.',
            'preco.min' => 'O preço deve ser maior que zero.',
            'preco.max' => 'O preço informado ultrapassa o limite permitido.',
            'quantidade.required' => 'Informe a quantidade disponível.',
            'quantidade.integer' => 'A quantidade deve ser um número inteiro.',
            'quantidade.min' => 'A quantidade não pode ser negativa.',
            'foto.required' => 'Selecione uma foto para o produto.',
            'foto.image' => 'O arquivo enviado precisa ser uma imagem.',
            'foto.mimes' => 'A foto deve estar nos formatos JPEG, JPG, PNG ou WEBP.',
            'foto.max' => 'A foto pode ter no máximo 5 MB.',
        ];
    }
}
