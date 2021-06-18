<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Http;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function all(): \Illuminate\Http\JsonResponse
    {
        $products = (new ProductService())->productOrderByCategories();

        return response()->json([
            'success' => true,
            'categories' => $products
        ]);
    }

    public function getProductsFromApi(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['success' => true , 'data' => (new ProductService())->productsUsingApi()]);
    }
}
