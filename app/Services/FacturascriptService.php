<?php


namespace App\Services;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FacturascriptService
{
    /**
     * @param string $resource
     * @return mixed
     */
    public function get(string $resource)
    {
        $fsApiUrl = env('FS_API_URL') .'/'. $resource;
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
    public function post(array $data, string $resource)
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
    public function put(int $resourceId, array $data, string $resource)
    {
        $fsApiUrl = env('FS_API_URL') .'/'.$resource.'/'.$resourceId;
        $apiKey = env('FS_API_KEY');

        $response = Http::withHeaders([
            'Token' => $apiKey,
        ])->asForm()->put($fsApiUrl, $data);

        return json_decode($response->getBody()->getContents());

    }




}
