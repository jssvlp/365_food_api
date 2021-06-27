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

    public function get($idproducto): \Illuminate\Http\JsonResponse
    {
        $product = Product::find($idproducto);

        if($product){

            $product['category'] = $product->category;
        }


        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }

    public function getProductsFromApi(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['success' => true , 'products' => (new ProductService())->productsUsingApi()]);
    }

    public function getProductsByFamily($codfamilia)
    {
        return response()->json([
            'success' => true,
            'message' => 'Consulta realizada correctamente!',
            'products' => Product::where('codfamilia', $codfamilia)->get()
        ]);
    }
}
