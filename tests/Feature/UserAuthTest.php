<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\User;

class UserAuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Se prueba el registro de un usuario simple.
     *
     * @return void
     */
    public function testSimpleSignUp()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/sign-up', [
            'name' => 'Alxis Javier',
            'username' => 'AxMedia',
            'email' => 'example@apicrud.com',
            'password' => 'secretPassword123456*'
        ]);

        $response->assertStatus(201);
    }

    /**
     * Se prueba el registro de un usuario completo.
     *
     * @return void
     */
    public function testFullSignUp()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/sign-up', [
            'name' => 'Alxis Javier',
            'email' => 'example@apicrud.com',
            'password' => 'secretPassword123456*',
            'username' => 'Axworkflow',
            'birth_day' => '04/02/1996'
        ]);

        $response->assertStatus(201);
    }

    /**
     * Se prueba el registro de un usuario simple.
     *
     * @return void
     */
    public function testSignIn()
    {
        $this->withoutExceptionHandling();
        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            null, 'Test Personal Access Client', env('APP_URL')
        );

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => \DateTime::class,
            'updated_at' => \DateTime::class,
        ]);

        $user = User::create([
            'username' => $this->faker->userName,
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => bcrypt('secretPassword123456*'),

        ]);

        $response = $this->post('/api/sign-in', [
            'email' => $user->email,
            'password' => 'secretPassword123456*'
        ]);

        $response->assertStatus(201);
    }

    /**
     * Se prueba el registro de un usuario simple.
     *
     * @return void
     */
    public function testWrongCredentialsAtSignIn()
    {
        $this->withoutExceptionHandling();
        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            null, 'Test Personal Access Client', env('APP_URL')
        );

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => \DateTime::class,
            'updated_at' => \DateTime::class,
        ]);

        $user = User::create([
            'username' => $this->faker->userName,
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => bcrypt('secretPassword123456*'),

        ]);

        $response = $this->post('/api/sign-in', [
            'email' => $user->email,
            'password' => 'secretPasswor456*'
        ]);

        $response->assertStatus(401);
    }

    /**
     * Se prueba el registro de un usuario completo.
     *
     * @return void
     */
    public function testMissingRequiredFieldsAtSignUp()
    {
        $response = $this->post('/api/sign-up', [
            'name' => 'Alxis Javier',
            'password' => 'secretPassword123456*',
            'birth_day' => '04/02/1996'
        ]);

        $response->assertJsonStructure(['errors', 'success']);

        $response->assertStatus(200);
    }

    /**
     * Se prueba el registro de un usuario completo.
     *
     * @return void
     */
    public function testInvalidFieldsAtSignUp()
    {
        $response = $this->post('/api/sign-up', [
            'name' => 'Alxis Javier',
            'email' => 'example',
            'password' => 'secretPassword123456*',
            'username' => 'Axworkflow',
            'birth_day' => '04/02/1996',
            'phone' => 'Afasasg2554'
        ]);

        $response->assertJsonStructure(['errors', 'success']);

        $response->assertStatus(200);
    }
}
