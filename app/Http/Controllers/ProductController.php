<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active();

        if ($request->filled('series') && in_array($request->series, ['hype', 'pongo'])) {
            $query->where('series', $request->series);
        }

        $products = $query->orderBy('series')->orderBy('name')->get();
        return view('products.index', compact('products'));
    }
}
