<?php

namespace Database\Factories;

use App\Models\Avaliacao;
use App\Models\Curso;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class AvaliacaoFactory extends Factory
{
    protected $model = Avaliacao::class;

    public function definition(): array
    {
        return [
            'nota' => fake()->numberBetween(0, 100),
            'comentario' => fake()->optional()->sentence(),
            'id_usuario' => Usuario::factory(),
            'id_curso' => Curso::factory(),
        ];
    }
}
