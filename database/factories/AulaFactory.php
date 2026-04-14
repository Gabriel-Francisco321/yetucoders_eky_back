<?php

namespace Database\Factories;

use App\Models\Aula;
use App\Models\Curso;
use Illuminate\Database\Eloquent\Factories\Factory;

class AulaFactory extends Factory
{
    protected $model = Aula::class;

    public function definition(): array
    {
        return [
            'id_curso' => Curso::factory(),
            'titulo' => fake()->sentence(3),
            'tipo' => fake()->randomElement(['video', 'texto', 'pdf']),
            'conteudo_url' => fake()->url(),
            'duracao' => fake()->numberBetween(5, 120),
            'ordem' => fake()->unique()->numberBetween(1, 1000),
        ];
    }
}
