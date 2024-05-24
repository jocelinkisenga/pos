<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class Product extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_products_can_be_renderad(): void
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
    }

    public function test_products_can_be_added_by_user(){
        $response = $this->post("products",[])->assertAccepted();
        $response->assertStatus(201);
    }
}
