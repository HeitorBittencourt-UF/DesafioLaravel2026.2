<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    /**
     * Armazena a contagem de aparições de cada nome de e-mail, como vão inserir dados fake 
     */
    protected static array $contagemNomes = [];

    public function definition(): array
    {
        // Cria um nome fake, como não quero Dr, Sr. e afins peguei o primeiro e o ultimo nome
        $nome = $this->faker->firstName() . ' ' . $this->faker->lastName();

        // Remove espaços, acentos e caracteres especiais
        $slugNome = Str::slug($nome, '_');
        
        // Caso tenha mais de um nome no contador ele comeca a adcionar uma especia de iterador
        if (!isset(self::$contagemNomes[$slugNome])) {
            self::$contagemNomes[$slugNome] = 0;
            $sufixo = ''; 
        } else {
            self::$contagemNomes[$slugNome]++;
            $sufixo = self::$contagemNomes[$slugNome]; // 1, 2, 3...
        }

        $email = $slugNome .  $sufixo . '@exemplo.com';

        return [
            'nome' => $nome,
            'email' => $email,
            'senha' => '123456',
            'tipo' => $this->faker->randomElement(['administrador', 'usuario']),    // Para gerar usuarios aleatorios entre administrador e usuario
            'cpf' => $this->faker->numerify('###########'), //cria com a quantidade ideal do cpf
            'data_nascimento' => $this->faker->dateTimeBetween('-65 years', '-18 years'),   //cria entre as idades de 65 e 18 anos
            'telefone' => $this->faker->numerify('##9####-####'), // Cria com DDD e o 9 na frente 
            'saldo' => $this->faker->randomFloat(2, 0, 5000), // Cria um fake de valor entre 0 e 5000 com 2 casas
            'foto' => null,
            'criador_id' => null,
        ];
    }
}
?>