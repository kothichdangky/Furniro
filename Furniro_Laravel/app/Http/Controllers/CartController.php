<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function cart(Request $request) {
        return response()->json(session('cart', []));
    }

    public function store(Request $request) {
        session(['cart' => $request->all()]);
        return response()->json(['message'=> 'lưu cart thành công']);
    }

    public function destroy(Request $request) {
        session()->forget('cart');
        return response()->json(['message'=> 'xoá cart thành công']);
    }
}
