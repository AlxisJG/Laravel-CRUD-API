<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\PassportTestCase;
use App\Item;

class ItemCRUDTest extends PassportTestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * Creamos un item simple.
     *
     * @return void
     */
    public function testCreateSimpleItem()
    {
        $response = $this->post('/api/v1/items', [
            'name' => $this->faker->name,
            'price' => 300.50,
            'quantity' => 2
        ], $this->headers);

        $response->assertStatus(201);
    }

    /*
     * Listamos items.
     *
     * @return void
     */
    public function testGetItemsPaginated()
    {
        factory(Item::class, 3)->create();
        $response = $this->get('/api/v1/items', $this->headers);

        dd($response->getContent());

        $response->assertStatus(201);
    }
}
