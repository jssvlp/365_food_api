<?php


namespace App\Services;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FacturascriptService
{

    /**
     * @param string $resource
     * @param string|null $resourceCode
     * @return mixed
     */
    protected function get(string $resource, string $resourceCode = null)
    {
        $fsApiUrl = env('FS_API_URL') .'/'. $resource;
        $fsApiUrl = $resourceCode != null ? $fsApiUrl.'/'.$resourceCode : $fsApiUrl;

        $apiKey = env('FS_API_KEY');

        $response = Http::withHeaders([
            'Token' => $apiKey,
        ])->get($fsApiUrl, []);

        return json_decode($response->getBody()->getContents());
    }


    /**
     * @param array $data
     * @param string $resource
     * @return mixed
     */
    protected function post(array $data, string $resource)
    {
        $fsApiUrl = env('FS_API_URL') .'/'.$resource;
        $apiKey = env('FS_API_KEY');
       
        $response = Http::withHeaders([
            'Token' => $apiKey,
        ])->asForm()->post($fsApiUrl, $data);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * @param int $resourceId
     * @param array $data
     * @param string $resource
     */
    protected function put(int $resourceId, array $data, string $resource)
    {
        $fsApiUrl = env('FS_API_URL') .'/'.$resource.'/'.$resourceId;
        $apiKey = env('FS_API_KEY');

        $response = Http::withHeaders([
            'Token' => $apiKey,
        ])->asForm()->put($fsApiUrl, $data);

        return json_decode($response->getBody()->getContents());

    }




}
