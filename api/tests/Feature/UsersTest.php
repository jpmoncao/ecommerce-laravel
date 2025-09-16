<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\CartItems;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_should_login_user(): void
    {
        $email = $this->faker->email();
        $password = "StrongPassword@123";
        $encryptedPassword = bcrypt($password);

        User::factory()->create([
            "email" => $email,
            "password" => $encryptedPassword
        ]);

        $response = $this->post('/api/login', [
            "email" => $email,
            "password" => $password
        ]);

        $response
            ->assertStatus(200)
            ->assertJson(["message" => "Login successful!"]);
    }

    public function test_should_create_user(): void
    {
        $userData = [
            'name' => $this->faker->name(),
            'born_date' => $this->faker->date(),
            'address' => $this->faker->address(),
            'cpf_cnpj' => "71651383820", // CPF obtido por um gerador online
            'email' => $this->faker->email(),
            'password' => "StrongPassword@123",
        ];

        $response = $this->post('/api/users', $userData);

        $this->assertDatabaseHas('users', ['name' => $userData['name']]);

        $response
            ->assertStatus(201)
            ->assertJson(["message" => "User listed successfully!"])
            ->assertJsonPath('data.name', $userData['name']);
    }

    public function test_should_list_user_by_id(): void
    {
        $user = User::factory()->create();

        $response = $this->get("/api/users/$user->id_user");

        $response
            ->assertStatus(200)
            ->assertJson(['message' => 'User listed successfully!'])
            ->assertJsonPath('data.id_user', $user->id_user);
    }

    public function test_should_list_user_items_in_cart_by_user_id(): void
    {
        $user = User::factory()->create();

        $cartItem = CartItems::factory()->create([
            'cart_id' => $user->id_user
        ]);

        $this->actingAs($user);

        $response = $this->get("/api/users/$cartItem->cart_id/cart-items");

        $response
            ->assertStatus(200)
            ->assertJson(['message' => 'Cart user items listed successfully!'])
            ->assertJsonPath('data.cart.id_cart', $user->id_user)
            ->assertJsonCount(1, 'data.cart.items');
    }
}
