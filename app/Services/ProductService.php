<?php


namespace App\Services;
//sudo certbot certonly --standalone --preferred-challenges http -d api-gop.gotdns.ch 
http://apigop.ddns.net/
use App\Models\Category;
use App\Models\Product;

/**
 * Class ProductService
 * @package App\Services
 */
class ProductService extends FacturascriptService
{
    public function productOrderByCategories(): array
    {
        $categories = Category::whereNotNull('orden')
                                ->orderBy('orden','ASC')
                                ->get();
        $productsCategories = [];

        foreach ($categories as $category)
        {
            $products = Product::where('codfamilia','=', $category->codfamilia)->get();
            
            if($products)
            {
                $_category = [
                    'name' => $category->descripcion,
                    'products' => $products
                ];
                array_push($productsCategories, $_category);
            }
            
        }
       
        return $productsCategories;
    }

    public function search()
    {
        
    }

    public function productsUsingApi()
    {
        return $this->get('productos');
    }

    public function getStocks()
    {
        return $this->get('stocks');
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

        return  $this->put($stockId, $data, 'stocks');
    }

    public function getStock(int $productId)
    {
        $stocks = collect($this->getStocks());

        return $stocks->filter(function ($stock) use ($productId){
             return $stock->idproducto == $productId;
        })->first();
    }
}
