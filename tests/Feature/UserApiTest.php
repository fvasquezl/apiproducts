<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     * @test
     * @return void
     */
    public function create_user_through_api_test()
    {
        $this->withoutExceptionHandling();
        $response = $this->postJson(route('users.store'), $this->userValidData());

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'verified' => User::USUARIO_NO_VERIFICADO,
                    'admin' => User::USUARIO_REGULAR,
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Faustino',
            'email' => 'fvasquez@local.com',
        ]);
    }


    /**
     * A basic test example.
     * @test
     * @return void
     */
    public function update_user_through_api_test()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create([
                'verified' => User::USUARIO_VERIFICADO
            ]);

        $admin = factory(User::class)->create($this->userValidData([
            'admin'=>User::USUARIO_ADMINISTRADOR
        ]));
        $this->actingAs($admin);
        $response= $this->putJson(route('users.update',$user),[
            'name'=>'Sebastian',
            'email'=> 'svasquez@local.com',
            'password' => 'mysecret',
            'password_confirmation' => 'mysecret',
            'admin'=>User::USUARIO_ADMINISTRADOR,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name'=>'Sebastian',
                    'email'=> 'svasquez@local.com',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'name'=>'Sebastian',
            'email'=> 'svasquez@local.com',
        ]);
    }

    /**
     * A basic test example.
     * @test
     * @return void
     */
    public function only_verified_users_can_change_admin_status_of_user_test()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create([
            'verified' => User::USUARIO_VERIFICADO
        ]);

        $otherUser = factory(User::class)->create($this->userValidData([
            'verified' => User::USUARIO_NO_VERIFICADO,
            'admin'=>User::USUARIO_REGULAR
        ]));
        $this->actingAs($otherUser);
        $response= $this->putJson(route('users.update',$user),[
            'name'=>'Sebastian',
            'email'=> 'svasquez@local.com',
            'password' => 'mysecret',
            'password_confirmation' => 'mysecret',
            'admin'=>User::USUARIO_ADMINISTRADOR
        ]);

        $response->assertStatus(409);
        $response->assertJson([
            'errors' => 'Unicamente los usuarios verificados pueden cambiar su valor de administrator'
        ]);

        $this->assertDatabaseCount('users',2);
    }


    /**
     * A basic test example.
     * @test
     * @return void
     */
    public function empty_request_generate_an_error_test()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();

        $admin = factory(User::class)->create($this->userValidData([
            'verified' => User::USUARIO_VERIFICADO,
            'admin'=>User::USUARIO_ADMINISTRADOR
        ]));
        $this->actingAs($admin);
        $response= $this->putJson(route('users.update',$user),[]);

        $response->assertStatus(422);
        $response->assertJson([
            'errors' => 'Se debe especificar al menos un valor diferente para actualizar'
        ]);

        $this->assertDatabaseCount('users',2);
    }

    /**
     * @param array $overrides
     * @return array
     */
    protected function userValidData($overrides = []): array
    {
        return array_merge([
            'name' => 'Faustino',
            'email' => 'fvasquez@local.com',
            'password' => 'secret',
        ], $overrides);
    }
}
