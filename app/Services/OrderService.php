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

        if($order->ok === 'Record updated correctly!')
        {
            foreach($request->items as $item)
            {
                $item['idfactura'] = $order->data->idfactura;
                $itemInsert = $facturascriptService->post($item, 'lineafacturaclientes');

                //TODO: actualizar el stock
                $productService = new ProductService();

                $productService->getStock($item['idproducto']);
            }
        }

        return new FacturascriptResponse(
            true,
            'Factura creada correctamente!'
        );
    }
}
