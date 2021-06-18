<?php


namespace App\Services;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FacturascriptService
{
    /**
     * @param Request $request
     */
    public function createInvoice(Request $request)
    {
        $fsApiUrl = env('FS_API_URL') . '/facturaclientes';


    }

    public function getProducts()
    {
        $fsApiUrl = env('FS_API_URL') . '/productos';
        $apiKey = env('FS_API_KEY');

        $response = Http::withHeaders([
            'Token' => $apiKey,
        ])->get($fsApiUrl, []);

        return json_decode($response->getBody()->getContents());
    }




}
