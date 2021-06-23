<?php


namespace App\Services;


use App\Helpers\FacturascriptResponse;
use Illuminate\Http\Request;

class ClientService extends FacturascriptService
{

    public function create(Request $request): FacturascriptResponse
    {
        $client = $this->post($request->all(),'clientes');

        if(!isset($client->ok) && isset($client->error)){
            return new FacturascriptResponse(
                false,
                $client->error
            );
        }

        return new FacturascriptResponse(
            true,
            'Cliente creado correctamente!'
        );

    }
}
