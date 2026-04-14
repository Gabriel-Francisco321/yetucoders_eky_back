<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Instrutor;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstrutorTest extends TestCase
{
    use RefreshDatabase;

    private Usuario $usuario;

    protected function setUp(): void
    {
        parent::setUp();
        $this->usuario = Usuario::factory()->create(['tipo' => 'instrutor']);
    }

    public function test_list_instrutores(): void
    {
        Instrutor::factory()->count(2)->create();

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->getJson('/api/instrutores');

        $response->assertStatus(200);
    }

    public function test_show_instrutor(): void
    {
        $instrutor = Instrutor::factory()->create();

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->getJson("/api/instrutores/{$instrutor->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['biografia' => $instrutor->biografia]);
    }

    public function test_create_instrutor(): void
    {
        $novoUsuario = Usuario::factory()->create(['tipo' => 'instrutor']);

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/instrutores', [
                'id_usuario' => $novoUsuario->id,
                'biografia' => 'Esta é uma biografia com pelo menos vinte caracteres para passar a validação.',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.usuario.id', $novoUsuario->id);

        $this->assertDatabaseHas('instrutores', ['id_usuario' => $novoUsuario->id]);
    }

    public function test_update_instrutor(): void
    {
        $instrutor = Instrutor::factory()->create();

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->putJson("/api/instrutores/{$instrutor->id}", [
                'biografia' => 'Biografia actualizada com mais de vinte caracteres necessários.',
            ]);

        $response->assertStatus(200);
    }

    public function test_delete_instrutor(): void
    {
        $instrutor = Instrutor::factory()->create();

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->deleteJson("/api/instrutores/{$instrutor->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('instrutores', ['id' => $instrutor->id]);
    }

    public function test_restore_instrutor(): void
    {
        $instrutor = Instrutor::factory()->create();
        $instrutor->delete();

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson("/api/instrutores/{$instrutor->id}/restore");

        $response->assertStatus(200);
    }

    public function test_force_delete_instrutor(): void
    {
        $instrutor = Instrutor::factory()->create();
        $instrutor->delete();

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->deleteJson("/api/instrutores/{$instrutor->id}/force");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('instrutores', ['id' => $instrutor->id]);
    }

    public function test_create_instrutor_validation(): void
    {
        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/instrutores', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id_usuario', 'biografia']);
    }

    public function test_create_instrutor_duplicate_usuario(): void
    {
        $instrutor = Instrutor::factory()->create();

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/instrutores', [
                'id_usuario' => $instrutor->id_usuario,
                'biografia' => 'Uma biografia duplicada com mais de vinte caracteres.',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('id_usuario');
    }
}
