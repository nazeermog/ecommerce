<?php

namespace Tests\Unit;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_product_has_fillable_attributes()
    {
        $product = new Product();
        
        $this->assertEquals([
            'name',
            'price',
            'image',
        ], $product->getFillable());
    }

    public function test_product_price_is_cast_to_decimal()
    {
        $product = Product::factory()->create(['price' => 99.99]);
        
        $this->assertIsString($product->price);
        $this->assertEquals('99.99', $product->price);
    }

    public function test_product_can_be_created_with_factory()
    {
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'price' => 149.99,
            'image' => 'https://example.com/image.jpg'
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 149.99,
            'image' => 'https://example.com/image.jpg'
        ]);
    }
}
