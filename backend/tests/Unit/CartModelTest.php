<?php

namespace Tests\Unit;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_cart_belongs_to_user()
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $cart->user);
        $this->assertEquals($user->id, $cart->user->id);
    }

    public function test_cart_has_products_with_quantity_pivot()
    {
        $cart = Cart::factory()->create();
        $product = Product::factory()->create();

        // Attach product with quantity on the pivot table
        $cart->products()->attach($product->id, ['quantity' => 3]);

        $cart->load('products');

        $this->assertCount(1, $cart->products);
        $this->assertEquals(3, $cart->products->first()->pivot->quantity);
        $this->assertInstanceOf(Product::class, $cart->products->first());
    }

    public function test_cart_total_calculation()
    {
        $cart = Cart::factory()->create();

        $product1 = Product::factory()->create(['price' => 50.00]);
        $product2 = Product::factory()->create(['price' => 30.00]);

        $cart->products()->attach($product1->id, ['quantity' => 2]);
        $cart->products()->attach($product2->id, ['quantity' => 1]);

        $cart->load('products');

        // Calculate total manually
        $total = $cart->products->sum(function ($product) {
            return $product->price * $product->pivot->quantity;
        });

        $this->assertEquals(130.00, $total);
    }

    public function test_user_can_have_only_one_cart()
    {
        $user = User::factory()->create();

        $cart1 = Cart::create(['user_id' => $user->id]);
        $this->assertDatabaseHas('carts', ['id' => $cart1->id]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Cart::create(['user_id' => $user->id]);
    }
}
