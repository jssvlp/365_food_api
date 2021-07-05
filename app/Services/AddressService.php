<?php


namespace App\Services;


use App\Helpers\FacturascriptResponse;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressService extends FacturascriptService
{
    public function create(Request $request): FacturascriptResponse
    {
        $data = $request->all();
        $data['codpais'] = 'DOP';
        $data['provincia'] = 'Santo Domingo';
        $data['cifnif'] = "";

        $created = $this->post($data, 'contactos');

        if(!isset($created->ok) && isset($created->error)){
            return new FacturascriptResponse(
                false,
                $created->error
            );
        }

        return new FacturascriptResponse(
            true,
            'Dirección almacenada correctamente!',
            (array)$created
        );
    }

    public function byClient($codcliente)
    {
        return  Address::where('codcliente', $codcliente)->get();
    }
}
