<?php

namespace Database\Factories;

use App\Models\Curso;
use App\Models\Instrutor;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class CursoFactory extends Factory
{
    protected $model = Curso::class;

    public function definition(): array
    {
        return [
            'titulo' => fake()->sentence(3),
            'descricao' => fake()->paragraph(),
            'objectivos' => fake()->sentence(6),
            'requisitos' => fake()->optional()->sentence(4),
            'preco' => fake()->optional()->randomFloat(2, 0, 500),
            'nivel' => fake()->randomElement(['Iniciante', 'Intermediário', 'Avançado']),
            'id_instrutor' => Instrutor::factory(),
            'id_categoria' => Categoria::factory(),
        ];
    }
}
