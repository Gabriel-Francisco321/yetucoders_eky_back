<?php

namespace Database\Factories;

use App\Models\Instrutor;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstrutorFactory extends Factory
{
    protected $model = Instrutor::class;

    public function definition(): array
    {
        return [
            'id_usuario' => Usuario::factory(),
            'biografia' => fake()->paragraph(3),
        ];
    }
}
