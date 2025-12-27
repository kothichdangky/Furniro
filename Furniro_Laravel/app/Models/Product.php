<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public function extraImages()
    {
        return $this->hasMany(ImagesProduct::class, 'product_id');
    }

    public function sizes()
    {
        return $this->hasMany(ProductsSize::class, 'product_id');
    }

    public function getTotalQuantity()
    {
        return $this->sizes->sum('quantity');
    }

}
