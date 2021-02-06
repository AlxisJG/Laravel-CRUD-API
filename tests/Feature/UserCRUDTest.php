<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\PassportTestCase;
use App\User;

class UserCRUDTest extends PassportTestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * Creamos un item simple.
     *
     * @return void
     */
    public function testCreateUser()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/v1/users', [
            'name' => 'Prueba 1',
            'email' => 'prueba1@gmail.com',
            'username' => 'Prueba Username 1',
            'password' => '123456',
            'password_confirmation' => '123456'

        ], $this->headers);

        $response->assertStatus(201);
    }

    /**
     * Actualizamos un usuario.
     *
     * @return void
     */
    public function testUpdateUser()
    {
        $this->withoutExceptionHandling();
        $response = $this->put('/api/v1/users/1', [
            'name' => 'Prueba 1 editada'
        ], $this->headers);

        $response->assertStatus(201);
    }

    /*
     * Listamos items paginado.
     *
     * @return void
     */
    public function testGetUsersPaginated()
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/api/v1/users', $this->headers);

        $response->assertStatus(201);
    }

    /*
     * Mostrar un user por id.
     *
     * @return void
     */
    public function testGetUserById()
    {
        $response = $this->get('/api/v1/users/1', $this->headers);
        $response->assertStatus(201);
    }

    /*
     * Probamos cuando enviamos un id incorrecto o no existente.
     *
     * @return void
     */
    public function testWrongIdGettingItemById()
    {
        $response = $this->get('/api/v1/users/4', $this->headers);
        $response->assertStatus(404);
    }
}
