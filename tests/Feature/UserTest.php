<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $name;
    public $email;
    public $password;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register()
    {
        // fake
        $name = $this->faker->name;
        $email = $this->faker->email;
        $password = $this->faker->password;

        $data = compact('name', 'email', 'password');

        $response = $this->postJson('/api/register', $data);
        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login()
    {
        User::factory()->create([
            'email' => 'duc@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        $data = [
            'email' => 'duc@gmail.com',
            'password' => '12345678',
        ];

        $response = $this->postJson('/api/login', $data);
        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ]);
    }
}
