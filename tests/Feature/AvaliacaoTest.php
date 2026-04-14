<?php

namespace Tests\Feature;

use App\Models\Avaliacao;
use App\Models\Curso;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvaliacaoTest extends TestCase
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

    public function test_list_my_avaliacoes(): void
    {
        Avaliacao::factory()->create(['id_usuario' => $this->usuario->id, 'id_curso' => $this->curso->id]);

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->getJson('/api/avaliacoes');

        $response->assertStatus(200);
    }

    public function test_create_avaliacao(): void
    {
        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/avaliacoes', [
                'nota' => 85,
                'comentario' => 'Muito bom curso!',
                'id_curso' => $this->curso->id,
            ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['nota' => 85]);

        $this->assertDatabaseHas('avaliacoes', [
            'nota' => 85,
            'id_usuario' => $this->usuario->id,
            'id_curso' => $this->curso->id,
        ]);
    }

    public function test_delete_own_avaliacao(): void
    {
        $avaliacao = Avaliacao::factory()->create([
            'id_usuario' => $this->usuario->id,
            'id_curso' => $this->curso->id,
        ]);

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->deleteJson("/api/avaliacoes/{$avaliacao->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('avaliacoes', ['id' => $avaliacao->id]);
    }

    public function test_cannot_delete_other_users_avaliacao(): void
    {
        $outroUsuario = Usuario::factory()->create();
        $avaliacao = Avaliacao::factory()->create([
            'id_usuario' => $outroUsuario->id,
            'id_curso' => $this->curso->id,
        ]);

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->deleteJson("/api/avaliacoes/{$avaliacao->id}");

        $response->assertStatus(404);
    }

    public function test_avaliacoes_por_curso_is_public(): void
    {
        Avaliacao::factory()->count(3)->create(['id_curso' => $this->curso->id]);

        $response = $this->getJson("/api/cursos/{$this->curso->id}/avaliacoes");

        $response->assertStatus(200)
            ->assertJsonStructure(['avaliacoes', 'classificacao']);
    }

    public function test_create_avaliacao_validation(): void
    {
        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/avaliacoes', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nota', 'id_curso']);
    }

    public function test_nota_must_be_between_0_and_100(): void
    {
        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/avaliacoes', [
                'nota' => 150,
                'id_curso' => $this->curso->id,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('nota');
    }
}
