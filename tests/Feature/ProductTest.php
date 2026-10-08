<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_page_can_be_displayed(): void
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
    }

    public function test_product_can_be_created(): void
    {
        $response = $this->post('/products', [
            'name' => 'Laptop',
            'price' => 10000000,
            'description' => 'Laptop untuk kuliah',
        ]);

        $response->assertRedirect('/products');

        $this->assertDatabaseHas('products', [
            'name' => 'Laptop',
            'price' => 10000000,
        ]);
    }

    public function test_product_can_be_updated(): void
    {
        $product = Product::create([
            'name' => 'Laptop',
            'price' => 10000000,
            'description' => 'Laptop lama',
        ]);

        $response = $this->put("/products/{$product->id}", [
            'name' => 'Laptop Gaming',
            'price' => 15000000,
            'description' => 'Laptop gaming baru',
        ]);

        $response->assertRedirect('/products');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Laptop Gaming',
            'price' => 99999999,
        ]);
    }

    public function test_product_can_be_deleted(): void
    {
        $product = Product::create([
            'name' => 'Laptop',
            'price' => 10000000,
            'description' => 'Laptop untuk kuliah',
        ]);

        $response = $this->delete("/products/{$product->id}");

        $response->assertRedirect('/products');

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }
}