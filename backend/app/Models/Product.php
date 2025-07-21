<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'image',
    ];
    protected $casts = [
        'price' => 'decimal:2',
    ];
    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_items')
            ->withPivot('quantity')
            ->withTimestamps();
    }
    public function getImageUrl()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
