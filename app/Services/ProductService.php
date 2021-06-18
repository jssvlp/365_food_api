<?php


namespace App\Services;


use App\Models\Category;
use App\Models\Product;

class ProductService
{
    private $facturascriptService;

    public function __construct()
    {
        $this->facturascriptService = new FacturascriptService();
    }

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
        return $this->facturascriptService->get('products');
    }

    public function getStocks()
    {
        return $this->facturascriptService->get('stocks');
    }

    public function updateStock(int $stockId, int $stock)
    {
        $data = [
            'disponible' => $stock,
            'cantidad' => $stock
        ];
    }

    public function getStock(int $productId)
    {
        $stocks = collect($this->getStocks());

        $stock =  $stocks->map(function ($stock) use ($productId){
             dump($stock);
        });
        exit();
        dd($stock);
    }
}
