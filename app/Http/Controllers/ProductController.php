<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;

class ProductController extends Controller
{
    public function all()
    {
        $categories = Category::all();
        $productsCategories = [];

        foreach ($categories as $category)
        {
            $products = Product::where('codfamilia', $category->codfamilia)->get();

            $_category = [
                'name' => $category->descripcion,
                'products' => $products
            ];

            array_push($productsCategories, $_category);
        }

        
        return response()->json([
            'success' => true,
            'categories' => $productsCategories
        ]);
    }
}
