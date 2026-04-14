<?php

namespace Database\Seeders;

use App\Models\Aula;
use App\Models\Avaliacao;
use App\Models\Categoria;
use App\Models\Curso;
use App\Models\Instrutor;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuarios
        $admin = Usuario::create([
            'nome' => 'Admin EKY',
            'email' => 'admin@eky.ao',
            'senha' => Hash::make('password'),
            'tipo' => 'instrutor',
        ]);

        $instrutor2 = Usuario::create([
            'nome' => 'Maria Fernandes',
            'email' => 'maria@eky.ao',
            'senha' => Hash::make('password'),
            'tipo' => 'instrutor',
        ]);

        $aluno = Usuario::create([
            'nome' => 'Aluno Teste',
            'email' => 'aluno@eky.ao',
            'senha' => Hash::make('password'),
            'tipo' => 'aluno',
        ]);

        $aluno2 = Usuario::create([
            'nome' => 'João Silva',
            'email' => 'joao@eky.ao',
            'senha' => Hash::make('password'),
            'tipo' => 'aluno',
        ]);

        // Categorias
        $catProg = Categoria::create(['titulo' => 'Programação', 'descricao' => 'Cursos de desenvolvimento de software']);
        $catDesign = Categoria::create(['titulo' => 'Design', 'descricao' => 'Cursos de design gráfico e UI/UX']);
        $catMarketing = Categoria::create(['titulo' => 'Marketing', 'descricao' => 'Cursos de marketing digital']);

        // Instrutores
        $inst1 = Instrutor::create([
            'id_usuario' => $admin->id,
            'biografia' => 'Desenvolvedor sénior com mais de 10 anos de experiência em tecnologias web e mobile.',
        ]);

        $inst2 = Instrutor::create([
            'id_usuario' => $instrutor2->id,
            'biografia' => 'Designer e especialista em UX com experiência em empresas internacionais.',
        ]);

        // Cursos
        $cursoLaravel = Curso::create([
            'titulo' => 'Laravel do Zero ao Avançado',
            'descricao' => 'Aprenda Laravel desde os fundamentos até técnicas avançadas de desenvolvimento.',
            'objectivos' => 'Dominar o framework Laravel para criar APIs e aplicações web completas.',
            'requisitos' => 'Conhecimentos básicos de PHP e SQL.',
            'preco' => 49.99,
            'nivel' => 'Intermediário',
            'id_instrutor' => $inst1->id,
            'id_categoria' => $catProg->id,
        ]);

        $cursoUX = Curso::create([
            'titulo' => 'UX Design Fundamentals',
            'descricao' => 'Introdução completa ao design de experiência do utilizador.',
            'objectivos' => 'Criar protótipos e interfaces centradas no utilizador.',
            'requisitos' => null,
            'preco' => 29.99,
            'nivel' => 'Iniciante',
            'id_instrutor' => $inst2->id,
            'id_categoria' => $catDesign->id,
        ]);

        $cursoMarketing = Curso::create([
            'titulo' => 'Marketing Digital para Iniciantes',
            'descricao' => 'Estratégias essenciais de marketing digital para alavancar negócios.',
            'objectivos' => 'Compreender SEO, redes sociais e campanhas de email marketing.',
            'requisitos' => null,
            'preco' => null,
            'nivel' => 'Iniciante',
            'id_instrutor' => $inst1->id,
            'id_categoria' => $catMarketing->id,
        ]);

        // Aulas do curso Laravel
        Aula::create(['id_curso' => $cursoLaravel->id, 'titulo' => 'Instalação e Configuração', 'tipo' => 'video', 'conteudo_url' => 'https://example.com/aula1', 'duracao' => 15, 'ordem' => 1]);
        Aula::create(['id_curso' => $cursoLaravel->id, 'titulo' => 'Rotas e Controllers', 'tipo' => 'video', 'conteudo_url' => 'https://example.com/aula2', 'duracao' => 25, 'ordem' => 2]);
        Aula::create(['id_curso' => $cursoLaravel->id, 'titulo' => 'Eloquent ORM', 'tipo' => 'video', 'conteudo_url' => 'https://example.com/aula3', 'duracao' => 30, 'ordem' => 3]);

        // Aulas do curso UX
        Aula::create(['id_curso' => $cursoUX->id, 'titulo' => 'O que é UX Design?', 'tipo' => 'texto', 'conteudo_url' => 'https://example.com/ux1', 'duracao' => 10, 'ordem' => 1]);
        Aula::create(['id_curso' => $cursoUX->id, 'titulo' => 'Pesquisa de Utilizadores', 'tipo' => 'pdf', 'conteudo_url' => 'https://example.com/ux2', 'duracao' => 20, 'ordem' => 2]);

        // Inscrições
        $aluno->cursos()->attach([$cursoLaravel->id, $cursoUX->id]);
        $aluno2->cursos()->attach([$cursoLaravel->id, $cursoMarketing->id]);

        // Avaliações
        Avaliacao::create(['nota' => 90, 'comentario' => 'Excelente curso, muito bem explicado!', 'id_usuario' => $aluno->id, 'id_curso' => $cursoLaravel->id]);
        Avaliacao::create(['nota' => 75, 'comentario' => 'Bom conteúdo, mas poderia ter mais exemplos.', 'id_usuario' => $aluno2->id, 'id_curso' => $cursoLaravel->id]);
        Avaliacao::create(['nota' => 85, 'comentario' => 'Muito útil para iniciantes.', 'id_usuario' => $aluno->id, 'id_curso' => $cursoUX->id]);
    }
}
