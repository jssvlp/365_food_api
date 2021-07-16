<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryMan extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';

    public function orders()
    {
        return $this->hasMany(Order::class, 'deliveryManId');
    }
}
