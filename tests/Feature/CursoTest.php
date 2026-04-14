<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Curso;
use App\Models\Instrutor;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CursoTest extends TestCase
{
    use RefreshDatabase;

    private Usuario $usuario;
    private Instrutor $instrutor;
    private Categoria $categoria;

    protected function setUp(): void
    {
        parent::setUp();
        $this->usuario = Usuario::factory()->create();
        $this->instrutor = Instrutor::factory()->create();
        $this->categoria = Categoria::factory()->create();
    }

    public function test_list_cursos_is_public(): void
    {
        Curso::factory()->count(2)->create();

        $response = $this->getJson('/api/cursos');

        $response->assertStatus(200);
    }

    public function test_show_curso_is_public(): void
    {
        $curso = Curso::factory()->create();

        $response = $this->getJson("/api/cursos/{$curso->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['titulo' => $curso->titulo]);
    }

    public function test_create_curso(): void
    {
        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/cursos', [
                'titulo' => 'Novo Curso',
                'descricao' => 'Descrição do novo curso',
                'objectivos' => 'Objectivos do novo curso',
                'requisitos' => null,
                'preco' => 25.00,
                'nivel' => 'Iniciante',
                'id_instrutor' => $this->instrutor->id,
                'id_categoria' => $this->categoria->id,
            ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['titulo' => 'Novo Curso']);

        $this->assertDatabaseHas('cursos', ['titulo' => 'Novo Curso']);
    }

    public function test_update_curso(): void
    {
        $curso = Curso::factory()->create([
            'id_instrutor' => $this->instrutor->id,
            'id_categoria' => $this->categoria->id,
        ]);

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->putJson("/api/cursos/{$curso->id}", [
                'titulo' => 'Curso Actualizado',
                'descricao' => 'Nova descrição',
                'objectivos' => 'Novos objectivos',
                'requisitos' => null,
                'preco' => 30.00,
                'nivel' => 'Avançado',
                'id_instrutor' => $this->instrutor->id,
                'id_categoria' => $this->categoria->id,
            ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['titulo' => 'Curso Actualizado']);
    }

    public function test_delete_curso(): void
    {
        $curso = Curso::factory()->create();

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->deleteJson("/api/cursos/{$curso->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('cursos', ['id' => $curso->id]);
    }

    public function test_restore_curso(): void
    {
        $curso = Curso::factory()->create();
        $curso->delete();

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson("/api/cursos/{$curso->id}/restore");

        $response->assertStatus(200);
    }

    public function test_force_delete_curso(): void
    {
        $curso = Curso::factory()->create();
        $curso->delete();

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->deleteJson("/api/cursos/{$curso->id}/force");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('cursos', ['id' => $curso->id]);
    }

    public function test_create_curso_validation(): void
    {
        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/cursos', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['titulo', 'descricao', 'objectivos', 'nivel', 'id_instrutor', 'id_categoria']);
    }

    public function test_show_nonexistent_curso_returns_404(): void
    {
        $response = $this->getJson('/api/cursos/99999');

        $response->assertStatus(404);
    }
}
