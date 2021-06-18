<?php


namespace App\Services;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FacturascriptService
{
    public function get(string $resource)
    {
        $fsApiUrl = env('FS_API_URL') .'/'. $resource;
        $apiKey = env('FS_API_KEY');

        $response = Http::withHeaders([
            'Token' => $apiKey,
        ])->get($fsApiUrl, []);

        return json_decode($response->getBody()->getContents());
    }

    public function post(array $data, string $resource)
    {
        $fsApiUrl = env('FS_API_URL') .'/'.$resource;
        $apiKey = env('FS_API_KEY');

        $response = Http::withHeaders([
            'Token' => $apiKey,
        ])->asForm()->post($fsApiUrl, $data);

        return json_decode($response->getBody()->getContents());
    }

    public function put(int $resourceId, array $data, string $resource)
    {


    }




}
