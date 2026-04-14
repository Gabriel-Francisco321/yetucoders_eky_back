<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsuarioTest extends TestCase
{
    use RefreshDatabase;

    private Usuario $usuario;

    protected function setUp(): void
    {
        parent::setUp();
        $this->usuario = Usuario::factory()->create();
    }

    public function test_list_usuarios(): void
    {
        Usuario::factory()->count(3)->create();

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->getJson('/api/usuarios');

        $response->assertStatus(200);
    }

    public function test_show_usuario(): void
    {
        $response = $this->actingAs($this->usuario, 'sanctum')
            ->getJson("/api/usuarios/{$this->usuario->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['nome' => $this->usuario->nome]);
    }

    public function test_create_usuario(): void
    {
        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/usuarios', [
                'nome' => 'Novo Aluno',
                'email' => 'novoaluno@eky.ao',
                'senha' => 'password123',
                'tipo' => 'aluno',
            ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['nome' => 'Novo Aluno']);

        $this->assertDatabaseHas('usuarios', ['email' => 'novoaluno@eky.ao']);
    }

    public function test_update_usuario(): void
    {
        $alvo = Usuario::factory()->create(['nome' => 'Original']);

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->putJson("/api/usuarios/{$alvo->id}", [
                'nome' => 'Actualizado',
            ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['nome' => 'Actualizado']);
    }

    public function test_delete_usuario(): void
    {
        $alvo = Usuario::factory()->create();

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->deleteJson("/api/usuarios/{$alvo->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('usuarios', ['id' => $alvo->id]);
    }

    public function test_list_trashed_usuarios(): void
    {
        $alvo = Usuario::factory()->create();
        $alvo->delete();

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->getJson('/api/usuarios/trashed/list');

        $response->assertStatus(200);
    }

    public function test_restore_usuario(): void
    {
        $alvo = Usuario::factory()->create();
        $alvo->delete();

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson("/api/usuarios/{$alvo->id}/restore");

        $response->assertStatus(200);
    }

    public function test_force_delete_usuario(): void
    {
        $alvo = Usuario::factory()->create();
        $alvo->delete();

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->deleteJson("/api/usuarios/{$alvo->id}/force");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('usuarios', ['id' => $alvo->id]);
    }

    public function test_create_usuario_validation(): void
    {
        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/usuarios', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nome', 'email', 'senha', 'tipo']);
    }

    public function test_show_nonexistent_usuario_returns_404(): void
    {
        $response = $this->actingAs($this->usuario, 'sanctum')
            ->getJson('/api/usuarios/99999');

        $response->assertStatus(404);
    }
}
