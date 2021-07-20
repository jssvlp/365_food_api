<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Address extends  Model
{
    protected $connection = 'mysql';
    protected $table = 'contactos';


    public function orders()
    {
        return $this->hasMany(Order::class,'addressId');
    }
}

