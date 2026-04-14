<?php

namespace Tests\Feature;

use App\Models\Curso;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CursoUsuarioTest extends TestCase
{
    use RefreshDatabase;

    private Usuario $usuario;
    private Curso $curso;

    protected function setUp(): void
    {
        parent::setUp();
        $this->usuario = Usuario::factory()->create(['tipo' => 'aluno']);
        $this->curso = Curso::factory()->create();
    }

    public function test_inscrever_em_curso(): void
    {
        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/inscrever', [
                'usuario_id' => $this->usuario->id,
                'curso_id' => $this->curso->id,
            ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Inscrição realizada com sucesso']);

        $this->assertDatabaseHas('curso_usuario', [
            'usuario_id' => $this->usuario->id,
            'curso_id' => $this->curso->id,
        ]);
    }

    public function test_inscrever_duplicado_retorna_erro(): void
    {
        $this->usuario->cursos()->attach($this->curso->id);

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/inscrever', [
                'usuario_id' => $this->usuario->id,
                'curso_id' => $this->curso->id,
            ]);

        $response->assertStatus(409);
    }

    public function test_cancelar_inscricao(): void
    {
        $this->usuario->cursos()->attach($this->curso->id);

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/cancelar-inscricao', [
                'usuario_id' => $this->usuario->id,
                'curso_id' => $this->curso->id,
            ]);

        $response->assertStatus(200);
    }

    public function test_cursos_do_usuario(): void
    {
        $this->usuario->cursos()->attach($this->curso->id);

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->getJson("/api/usuarios/{$this->usuario->id}/cursos");

        $response->assertStatus(200);
    }

    public function test_usuarios_do_curso(): void
    {
        $this->usuario->cursos()->attach($this->curso->id);

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->getJson("/api/cursos/{$this->curso->id}/usuarios");

        $response->assertStatus(200);
    }

    public function test_inscrever_validation(): void
    {
        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/inscrever', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['usuario_id', 'curso_id']);
    }
}
