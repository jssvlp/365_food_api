<?php

namespace App\Models;

use App\Models\DeliveryMan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';

    protected $fillable = ['orderNumber', 'status','clientName','clientCode','phone','deliveryManId','address','orderDetail','orderType','paymentMethod','addressId','delivered'];

    public function deliveryMan()
    {
        return $this->belongsTo(DeliveryMan::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
