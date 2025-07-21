<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\AddToCartRequest;

class CartController extends Controller
{
  private function getOrCreateCart()
  {
    if (auth()->check()) {
      return Cart::firstOrCreate(['user_id' => auth()->id()]);
    }

    $sessionId = request()->header('X-Session-ID');
    if (!$sessionId) {
      $sessionId = (string) Str::uuid();
    }

    return Cart::firstOrCreate([
      'user_id' => null,
      'session_id' => $sessionId,
    ]);
  }

  public function add(AddToCartRequest $request)
  {
    $cart = $this->getOrCreateCart();

    $productId = $request->input('product_id');
    $quantity = $request->input('quantity', 1);

    $existing = $cart->products()->where('product_id', $productId)->first();

    if ($existing) {
      $cart->products()->updateExistingPivot($productId, [
        'quantity' => $existing->pivot->quantity + $quantity,
      ]);
    } else {
      $cart->products()->attach($productId, ['quantity' => $quantity]);
    }

    $response = ['message' => 'Product added to cart'];

    if (auth()->check()) {
      $response['user_id'] = auth()->id();
    } else {
      $response['session_id'] = $cart->session_id;
    }

    return response()->json($response);
  }

  public function list()
  {
    $cart = $this->getOrCreateCart();

    $cart->load('products');

    $response = [
      'products' => $cart->products->map(function ($product) {
        return [
          'id' => $product->id,
          'name' => $product->name,
          'price' => $product->price,
          'image' => $product->image,
          'quantity' => $product->pivot->quantity,
        ];
      }),
    ];

    if (auth()->check()) {
      $response['user_id'] = auth()->id();
    } else {
      $response['session_id'] = $cart->session_id;
    }

    return response()->json($response);
  }
}
