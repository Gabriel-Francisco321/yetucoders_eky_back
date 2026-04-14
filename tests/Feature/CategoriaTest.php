<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoriaTest extends TestCase
{
    use RefreshDatabase;

    private Usuario $usuario;

    protected function setUp(): void
    {
        parent::setUp();
        $this->usuario = Usuario::factory()->create();
    }

    public function test_list_categorias_is_public(): void
    {
        Categoria::factory()->count(3)->create();

        $response = $this->getJson('/api/categorias');

        $response->assertStatus(200);
    }

    public function test_show_categoria_is_public(): void
    {
        $categoria = Categoria::factory()->create();

        $response = $this->getJson("/api/categorias/{$categoria->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['titulo' => $categoria->titulo]);
    }

    public function test_create_categoria_requires_auth(): void
    {
        $response = $this->postJson('/api/categorias', [
            'titulo' => 'Nova Categoria',
            'descricao' => 'Descrição da categoria',
        ]);

        $response->assertStatus(401);
    }

    public function test_create_categoria(): void
    {
        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/categorias', [
                'titulo' => 'Nova Categoria',
                'descricao' => 'Descrição da categoria',
            ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['titulo' => 'Nova Categoria']);

        $this->assertDatabaseHas('categorias', ['titulo' => 'Nova Categoria']);
    }

    public function test_update_categoria(): void
    {
        $categoria = Categoria::factory()->create();

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->putJson("/api/categorias/{$categoria->id}", [
                'titulo' => 'Titulo Actualizado',
                'descricao' => 'Descrição actualizada',
            ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['titulo' => 'Titulo Actualizado']);
    }

    public function test_delete_categoria(): void
    {
        $categoria = Categoria::factory()->create();

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->deleteJson("/api/categorias/{$categoria->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('categorias', ['id' => $categoria->id]);
    }

    public function test_list_trashed_categorias(): void
    {
        $categoria = Categoria::factory()->create();
        $categoria->delete();

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->getJson('/api/categorias/trashed/list');

        $response->assertStatus(200);
    }

    public function test_restore_categoria(): void
    {
        $categoria = Categoria::factory()->create();
        $categoria->delete();

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson("/api/categorias/{$categoria->id}/restore");

        $response->assertStatus(200);
        $this->assertDatabaseHas('categorias', ['id' => $categoria->id, 'deleted_at' => null]);
    }

    public function test_force_delete_categoria(): void
    {
        $categoria = Categoria::factory()->create();
        $categoria->delete();

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->deleteJson("/api/categorias/{$categoria->id}/force");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('categorias', ['id' => $categoria->id]);
    }

    public function test_create_categoria_validation(): void
    {
        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/categorias', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['titulo', 'descricao']);
    }
}
