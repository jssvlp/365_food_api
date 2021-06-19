<?php


namespace App\Services;


use App\Models\Category;
use App\Models\Product;

/**
 * Class ProductService
 * @package App\Services
 */
class ProductService
{
    /**
     * @var FacturascriptService
     */
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
        return $this->facturascriptService->get('productos');
    }

    public function getStocks()
    {
        return $this->facturascriptService->get('stocks');
    }

    /**
     * @param int $stockId
     * @param int $newStock
     * @return mixed
     */
    public function updateStock(int $stockId, int $newStock)
    {
        $data = [
            'disponible' => $newStock,
            'cantidad' => $newStock
        ];

        return  $this->facturascriptService->put($stockId, $data, 'stocks');
    }

    public function getStock(int $productId)
    {
        $stocks = collect($this->getStocks());

        return $stocks->filter(function ($stock) use ($productId){
             return $stock->idproducto == $productId;
        })->first();
    }
}