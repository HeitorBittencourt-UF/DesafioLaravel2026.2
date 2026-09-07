<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class CepController extends Controller
{
    public function show(string $cep): JsonResponse
    {
        

        $cep = preg_replace('/\D/', '', $cep);

        if (strlen($cep) !== 8) {
            return response()->json([
                'message' => 'CEP inválido. Informe exatamente 8 números.',
            ], 422);
        }

        
        // Olha o Cep pela API
        try {
            $response = Http::acceptJson()
                ->timeout(5)
                ->get("https://viacep.com.br/ws/{$cep}/json/");
        } catch (ConnectionException $erro) {
            return response()->json([
                'message' => 'Não foi possível conectar ao ViaCEP.',
            ], 503);
        }

        //  ve se deu merda
        if ($response->failed()) {
            return response()->json([
                'message' => 'O ViaCEP não pôde processar a consulta.',
            ], 502);
        }

        $dados = $response->json();
   

        if (
            ($dados['erro'] ?? false) === true ||
            ($dados['erro'] ?? null) === 'true'
        ) {
            return response()->json([
                'message' => 'CEP não encontrado.',
            ], 404);
        }
        return response()->json([
            'cep' => $dados['cep'] ?? '',
            'logradouro' => $dados['logradouro'] ?? '',
            'complemento' => $dados['complemento'] ?? '',
            'unidade' => $dados['unidade'] ?? '',
            'bairro' => $dados['bairro'] ?? '',
            'localidade' => $dados['localidade'] ?? '',
            'uf' => $dados['uf'] ?? '',
            'estado' => $dados['estado'] ?? '',
            'regiao' => $dados['regiao'] ?? '',
            'ibge' => $dados['ibge'] ?? '',
            'gia' => $dados['gia'] ?? '',
            'ddd' => $dados['ddd'] ?? '',
            'siafi' => $dados['siafi'] ?? '',
        ]);
    }
}