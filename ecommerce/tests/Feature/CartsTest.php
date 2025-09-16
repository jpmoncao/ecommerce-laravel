<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartsTest extends TestCase
{
    public function test_should_list_cart_by_user_id(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get("/api/carts/$user->id_user");

        $response
            ->assertStatus(200)
            ->assertJson(['message' => 'Cart listed successfully!'])
            ->assertJsonPath('data.id_cart', $user->id_cart);
    }
}
