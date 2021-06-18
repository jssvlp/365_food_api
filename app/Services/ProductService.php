<?php


namespace App\Services;


use App\Models\Category;
use App\Models\Product;

class ProductService
{
    public function productOrderByCategories(): array
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
        return $productsCategories;
    }

    public function productsUsingApi()
    {
        return (new FacturascriptService())->get('products');
    }
}
