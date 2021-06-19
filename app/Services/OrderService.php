<?php


namespace App\Services;


use App\Helpers\FacturascriptResponse;
use Illuminate\Http\Request;

class OrderService
{
    public function createInvoice(Request $request): FacturascriptResponse
    {
        $facturascriptService = new FacturascriptService();
        $order = $facturascriptService->post($request->except('items'), 'facturaclientes');

        if(!isset($order->ok) && isset($order->error)){
            return new FacturascriptResponse(
                false,
                $order->error
            );
        }

        if($order->ok === 'Record updated correctly!')
        {
            $productService = new ProductService();
            foreach($request->items as $item)
            {
                $item['idfactura'] = $order->data->idfactura;
                $itemInserted = $facturascriptService->post($item, 'lineafacturaclientes');

                $stock = $productService->getStock($item['idproducto']);
                $newStock = (int) $stock->cantidad - (int) $item['stock'];
                $productService->updateStock($stock->idstock, $newStock);
            }
        }

        return new FacturascriptResponse(
            true,
            'Factura creada correctamente!'
        );
    }
}
