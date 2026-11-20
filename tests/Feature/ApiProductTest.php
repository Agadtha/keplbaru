<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_products_returns_json(): void
    {
        Product::create([
            'name' => 'Laptop',
            'price' => 10000000,
            'description' => 'Laptop untuk kuliah',
        ]);

        $this->getJson('/api/products')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['name' => 'Laptop']);
    }
}
