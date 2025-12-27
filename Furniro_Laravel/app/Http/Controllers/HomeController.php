<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function product(Request $request)
    {                       // đang gọi cái này nên phải request
        $perPage = $request->input('per_page', 8); // Mặc định là 8 nếu không truyền gì
        $products = Product::paginate($perPage);
        return response() -> json($products);
    }
}
