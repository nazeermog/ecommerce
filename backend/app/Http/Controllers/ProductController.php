<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
  public function index()
  {
     $products = Cache::remember('products.all', 60 * 60, function () {
        return Product::all();
    });

    return response()->json($products);
}

  public function store(StoreProductRequest $request)
  {
    $imagePath = null;
    if ($request->hasFile('image')) {
      $imagePath = $request->file('image')->store('products', 'public');
    }

    $product = Product::create([
      'name' => $request->name,
      'price' => $request->price,
      'image' => $imagePath,
    ]);
    Cache::forget('products.all');

    return response()->json($product, 201);
  }
}
