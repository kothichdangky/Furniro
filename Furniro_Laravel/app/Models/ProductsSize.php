<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsSize extends Model
{
    protected $table = 'products_sizes'; // Bắt buộc nếu không đúng chuẩn Laravel

    protected $fillable = ['product_id', 'size', 'quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
