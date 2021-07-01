<?php


namespace App\Services;


use App\Helpers\FacturascriptResponse;
use Illuminate\Http\Request;

class OrderService extends FacturascriptService
{


    public function createOrder(Request $request): FacturascriptResponse
    {

        $orderParams =  $request->except(['items','direccionorden']);

        $orderParams['codoperaciondoc'] = 'ESTANDAR';
        $orderParams['codsubtipodoc'] = 'FACTURAVENTA';
        $orderParams['totalcomision'] = 0;
        $orderParams['cifnif'] = trim('');
        $orderParams['codalmacen'] = 'ALG'; //implementar endpoint empresas
        $orderParams['irpf'] = 0;
        $orderParams['idempresa'] = 1;//implementar endpoint empresas
        $orderParams['nick'] = 'admin';
        $orderParams['fecha'] = date('d-m-Y');
        $orderParams['hora'] = date('H:m:s');
        $orderParams['nick'] = 'admin';
        $orderParams['codejercicio'] = date('Y');
        $orderParams['pagada'] = 0;
        $orderParams['codserie'] = 'A';

//direccionorden
        $order = $this->post($orderParams, 'facturaclientes');

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
                $itemInserted = $this->post($item, 'lineafacturaclientes');

                $stock = $productService->getStock($item['idproducto']);

                $newStock = (int) $stock->cantidad - (int) $item['stock'];
                $productService->updateStock($stock->idstock, $newStock);
            }
            $receipt = $this->createReceipt($order);

            if(!isset($receipt->ok) && isset($receipt->error))
            {
                return new FacturascriptResponse(
                    false,
                    $receipt->error
                );
            }
        }

        return new FacturascriptResponse(
            true,
            'Factura creada correctamente!',
            (array)$order->data
        );
    }

    public function createReceipt(object  $invoice)
    {
        $data = [
            'codcliente' => $invoice->data->codcliente,
            'coddivisa' => 'DOP',
            'codigofactura' => $invoice->data->codigo,
            'codpago' => 'TRANS',
            'fecha' => date('d-m-y'),
            'fechapago' => '',
            'gastos' => '0',
            'idempresa' => '1',
            'idfactura' => $invoice->data->idfactura,
            'importe' =>  $invoice->data->total,
            'liquidado' => 0,
            'nick' => 'admin',
            'numero' => 1,
            'observaciones' => null,
            'pagado' => 0,
            'vencimiento' => '16-12-2021'
        ];
        return $this->post($data, 'reciboclientes');
    }
}
