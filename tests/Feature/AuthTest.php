<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_user_and_returns_token(): void
    {
        $response = $this->postJson('/api/register', [
            'nome' => 'Novo Usuario',
            'email' => 'novo@eky.ao',
            'senha' => 'password123',
            'senha_confirmation' => 'password123',
            'tipo' => 'aluno',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['usuario', 'token']);

        $this->assertDatabaseHas('usuarios', ['email' => 'novo@eky.ao', 'tipo' => 'aluno']);
    }

    public function test_register_fails_with_invalid_data(): void
    {
        $response = $this->postJson('/api/register', [
            'nome' => '',
            'email' => 'invalido',
            'senha' => '123',
            'tipo' => 'admin',
        ]);

        $response->assertStatus(422);
    }

    public function test_register_fails_with_duplicate_email(): void
    {
        Usuario::factory()->create(['email' => 'existente@eky.ao']);

        $response = $this->postJson('/api/register', [
            'nome' => 'Teste',
            'email' => 'existente@eky.ao',
            'senha' => 'password123',
            'senha_confirmation' => 'password123',
            'tipo' => 'aluno',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_login_returns_token(): void
    {
        Usuario::factory()->create([
            'email' => 'login@eky.ao',
            'senha' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'login@eky.ao',
            'senha' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['usuario', 'token']);
    }

    public function test_login_fails_with_wrong_credentials(): void
    {
        Usuario::factory()->create([
            'email' => 'login@eky.ao',
            'senha' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'login@eky.ao',
            'senha' => 'wrong_password',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Credenciais inválidas']);
    }

    public function test_logout_revokes_token(): void
    {
        $usuario = Usuario::factory()->create([
            'senha' => Hash::make('password123'),
        ]);

        $token = $usuario->createToken('auth-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Sessão terminada com sucesso']);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_me_returns_authenticated_user(): void
    {
        $usuario = Usuario::factory()->create(['nome' => 'Eu Mesmo']);

        $response = $this->actingAs($usuario, 'sanctum')
            ->getJson('/api/me');

        $response->assertStatus(200)
            ->assertJsonFragment(['nome' => 'Eu Mesmo']);
    }

    public function test_protected_routes_require_authentication(): void
    {
        $this->getJson('/api/me')->assertStatus(401);
        $this->postJson('/api/logout')->assertStatus(401);
        $this->getJson('/api/usuarios')->assertStatus(401);
    }
}
