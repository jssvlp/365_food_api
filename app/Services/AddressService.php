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

    public function update($id, Request $request)
    {
        $updated = $this->put($id, $request->all(),'contactos');

        if(!isset($updated->ok) && isset($updated->error)){
            return new FacturascriptResponse(
                false,
                $updated->error
            );
        }

        return new FacturascriptResponse(
            true,
            'Dirección modificada correctamente!',
            (array)$updated
        );
    }

    public function byClient($codcliente)
    {
        return  Address::where('codcliente', $codcliente)->get();
    }

    public function getAddress($contactoId)
    {
        return Address::where('idcontacto', $contactoId)->first();
    }

    public function destroy($id)
    {
        $deleted =  $this->delete($id, 'contactos');

        if(!isset($deleted->ok) && isset($deleted->error)){
            return new FacturascriptResponse(
                false,
                $deleted->error
            );
        }

        return new FacturascriptResponse(
            true,
            'Dirección eliminada correctamente!',
            (array)$deleted->data
        );
    }
}
