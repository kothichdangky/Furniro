<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagesProduct extends Model
{
    protected $table = 'images_products'; // Bắt buộc nếu không đúng chuẩn Laravel

    protected $fillable = ['product_id', 'image'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
