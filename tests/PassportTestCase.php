<?php

namespace Tests;

use App\User;
use Laravel\Passport\ClientRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;

class PassportTestCase extends TestCase
{
    use DatabaseTransactions;

    protected $headers = [];
    protected $scopes = [];
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            null, 'Test Personal Access Client', env('APP_URL')
        );

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => \DateTime::class,
            'updated_at' => \DateTime::class,
        ]);

        $this->user = factory(User::class)->create();
        $token = $this->user->createToken('authToken', $this->scopes)->accessToken;
        $this->headers['Accept'] = 'application/json';
        $this->headers['Authorization'] = 'Bearer ' . $token;
    }
}
