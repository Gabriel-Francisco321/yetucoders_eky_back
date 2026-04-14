<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Usuario::create([
            'nome' => 'Admin EKY',
            'email' => 'admin@eky.ao',
            'senha' => Hash::make('password'),
            'tipo' => 'instrutor',
        ]);

        $aluno = Usuario::create([
            'nome' => 'Aluno Teste',
            'email' => 'aluno@eky.ao',
            'senha' => Hash::make('password'),
            'tipo' => 'aluno',
        ]);

        Categoria::create(['titulo' => 'Programação', 'descricao' => 'Cursos de desenvolvimento de software']);
        Categoria::create(['titulo' => 'Design', 'descricao' => 'Cursos de design gráfico e UI/UX']);
        Categoria::create(['titulo' => 'Marketing', 'descricao' => 'Cursos de marketing digital']);
    }
}
