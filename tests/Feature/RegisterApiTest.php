<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResisterApiTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * @test
     */
    public function should_新しいユーザーを作成して返却する()
    {
        $data = [
            'name' => 'test1234',
            'email' => 'test1234@gmail.com',
            'password' => 'test1234',
            'password_confirmation' => 'test1234'
        ];

        $response = $this->json('POST', route('register'), $data);

        $user = User::first();
        $this->assertEquals($data['name'], $user->name);

        $response->assertStatus(200)
                 ->assertJson(['name' => $user->name]);
    }
}
