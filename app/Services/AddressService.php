<?php


namespace App\Services;


use App\Helpers\FacturascriptResponse;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressService extends FacturascriptService
{
    public function create(Request $request): FacturascriptResponse
    {
        $created = $this->post($request->all(), 'contactos');

        if(!isset($created->ok) && isset($created->error)){
            return new FacturascriptResponse(
                false,
                $created->error
            );
        }

        return new FacturascriptResponse(
            true,
            'Consulta ejecutada correctamente',
            (array)$created
        );
    }

    public function byClient($codcliente)
    {
        return  Address::where('codcliente', $codcliente)->get();
    }
}
