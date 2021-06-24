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

    public function all()
    {
        $clients = $this->get('clientes');

        if(!isset($clients->ok) && isset($clients->error)){
            return new FacturascriptResponse(
                false,
                $clients->error
            );
        }

        return new FacturascriptResponse(
            true,
            'Consulta ejecutada correctamente',
            (array)$clients
        );
    }
    public function getClient($codcliente)
    {
        $client = $this->get('clientes',$codcliente);

        if(!isset($client->ok) && isset($client->error)){
            return new FacturascriptResponse(
                false,
                $client->error
            );
        }

        return new FacturascriptResponse(
            true,
            'Consulta ejecutada correctamente',
            (array)$client
        );
    }
}
