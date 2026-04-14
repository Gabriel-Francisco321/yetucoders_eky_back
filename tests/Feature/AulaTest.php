<?php

namespace Tests\Feature;

use App\Models\Aula;
use App\Models\Curso;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AulaTest extends TestCase
{
    use RefreshDatabase;

    private Usuario $usuario;
    private Curso $curso;

    protected function setUp(): void
    {
        parent::setUp();
        $this->usuario = Usuario::factory()->create();
        $this->curso = Curso::factory()->create();
    }

    public function test_list_aulas(): void
    {
        Aula::factory()->create(['id_curso' => $this->curso->id, 'ordem' => 1]);
        Aula::factory()->create(['id_curso' => $this->curso->id, 'ordem' => 2]);

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->getJson('/api/aulas');

        $response->assertStatus(200);
    }

    public function test_list_aulas_por_curso(): void
    {
        Aula::factory()->create(['id_curso' => $this->curso->id, 'ordem' => 1]);

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->getJson("/api/cursos/{$this->curso->id}/aulas");

        $response->assertStatus(200);
    }

    public function test_show_aula(): void
    {
        $aula = Aula::factory()->create(['id_curso' => $this->curso->id, 'ordem' => 1]);

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->getJson("/api/aulas/{$aula->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['titulo' => $aula->titulo]);
    }

    public function test_create_aula(): void
    {
        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/aulas', [
                'id_curso' => $this->curso->id,
                'titulo' => 'Primeira Aula',
                'tipo' => 'video',
                'conteudo_url' => 'https://example.com/video1',
                'duracao' => 30,
                'ordem' => 1,
            ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['titulo' => 'Primeira Aula']);

        $this->assertDatabaseHas('aulas', ['titulo' => 'Primeira Aula']);
    }

    public function test_update_aula(): void
    {
        $aula = Aula::factory()->create(['id_curso' => $this->curso->id, 'ordem' => 1]);

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->putJson("/api/aulas/{$aula->id}", [
                'id_curso' => $this->curso->id,
                'titulo' => 'Aula Actualizada',
                'tipo' => 'pdf',
                'conteudo_url' => 'https://example.com/pdf1',
                'duracao' => 15,
                'ordem' => 1,
            ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['titulo' => 'Aula Actualizada']);
    }

    public function test_delete_aula(): void
    {
        $aula = Aula::factory()->create(['id_curso' => $this->curso->id, 'ordem' => 1]);

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->deleteJson("/api/aulas/{$aula->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('aulas', ['id' => $aula->id]);
    }

    public function test_create_aula_validation(): void
    {
        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/aulas', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id_curso', 'titulo', 'tipo', 'conteudo_url', 'duracao', 'ordem']);
    }

    public function test_create_aula_duplicate_order_same_course(): void
    {
        Aula::factory()->create(['id_curso' => $this->curso->id, 'ordem' => 1]);

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/aulas', [
                'id_curso' => $this->curso->id,
                'titulo' => 'Duplicada',
                'tipo' => 'video',
                'conteudo_url' => 'https://example.com/dup',
                'duracao' => 10,
                'ordem' => 1,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('ordem');
    }
}
