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

    /**
     * Actualizamos un item.
     *
     * @return void
     */
    public function testUpdateItem()
    {
        factory(Item::class, 1)->create();
        $response = $this->put('/api/v1/items/1', [
            'name' => 'Producto Bueno',
            'price' => 300.50,
            'sku' => '0002245121',
            'quantity' => 2
        ], $this->headers);

        $response->assertStatus(201);
    }

    /*
     * Buscamos item por sku.
     *
     * @return void
     */
    public function testGetItemBySku()
    {
        $this->withoutExceptionHandling();
        factory(Item::class, 1)->create();
        $response = $this->get('/api/v1/items?sky=ABC123456', $this->headers);

        $response->assertStatus(201);
    }

    /*
     * Listamos items paginado.
     *
     * @return void
     */
    public function testGetItemsPaginated()
    {
        factory(Item::class, 20)->create();
        $response = $this->get('/api/v1/items', $this->headers);

        $response->assertStatus(201);
    }

    /*
     * Mostrar un item por id.
     *
     * @return void
     */
    public function testGetItemById()
    {
        factory(Item::class, 1)->create();
        $response = $this->get('/api/v1/items/1', $this->headers);

        $response->assertStatus(201);
    }

    /*
     * Probamos cuando enviamos un id incorrecto o no existente.
     *
     * @return void
     */
    public function testWrongIdGettingItemById()
    {
        factory(Item::class, 1)->create();
        $response = $this->get('/api/v1/items/4', $this->headers);

        $response->assertStatus(404);
    }
}
