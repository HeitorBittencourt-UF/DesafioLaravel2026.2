<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    /**
     * Armazena a contagem de aparições de cada nome de e-mail.
     */
    protected static array $contagemNomes = [];

    public function definition(): array
    {
        // 1. Gera Nome + Sobrenome (sem títulos como Dr., Sr., etc.)
        $nome = $this->faker->firstName() . ' ' . $this->faker->lastName();

        // 2. Remove espaços, acentos e caracteres especiais (ex: "Maria Souza" -> "mariasouza")
        $slugNome = Str::slug($nome, '_');
        
        // 3. Controle de repetição e sufixo sequencial (1, 2, 3...)
        if (!isset(self::$contagemNomes[$slugNome])) {
            self::$contagemNomes[$slugNome] = 0;
            $sufixo = ''; // 1ª aparição: sem número
        } else {
            self::$contagemNomes[$slugNome]++;
            $sufixo = self::$contagemNomes[$slugNome]; // 2ª em diante: 1, 2, 3...
        }

        $email = $slugNome .  $sufixo . '@exemplo.com';

        return [
            'nome' => $nome,
            'email' => $email,
            'senha' => '123456',
            'tipo' => $this->faker->randomElement(['administrador', 'usuario']),
            'cpf' => $this->faker->numerify('###########'),
            'data_nascimento' => $this->faker->dateTimeBetween('-65 years', '-18 years'),
            'telefone' => $this->faker->numerify('9####-####'),
            'saldo' => $this->faker->randomFloat(2, 0, 5000),
            'foto' => null,
            'criador_id' => null,
        ];
    }
}