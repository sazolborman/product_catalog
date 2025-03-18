<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Sample products (No database needed)
        $products = [
            ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
            ['id' => 2, 'name' => 'Smartphone', 'price' => 800],
            ['id' => 3, 'name' => 'Headphones', 'price' => 250],
            ['id' => 4, 'name' => 'Monitor', 'price' => 300],
            ['id' => 5, 'name' => 'Mouse', 'price' => 400],
            ['id' => 6, 'name' => 'Cable', 'price' => 100],
            ['id' => 7, 'name' => 'Keyboard', 'price' => 200],
            ['id' => 8, 'name' => 'Pen drave', 'price' => 500],
        ];

        return view('products.index', compact('products'));
    }
}
