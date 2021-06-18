<?php


namespace App\Helpers;


class FacturascriptResponse
{
    public $success;
    public $message;
    public $data;

    public function __construct(bool $success, string $message, array $data = null)
    {
        $this->success = $success;
        $this->message = $message;
        $this->data = $data;
    }
}
