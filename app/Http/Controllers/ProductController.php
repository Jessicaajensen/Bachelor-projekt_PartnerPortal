<?php

namespace App\Http\Controllers;

class ProductController extends Controller
{
    public function index()
    {
        $products = auth()->user()->partner->products;

        return view('products.index', ['products' => $products]);
    }

    public function show($id)
    {
        $product = auth()->user()->partner->products()->findOrFail($id);

        return view('products.show', ['product' => $product]);
    }
}