<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $fillable = ['orderNumber', 'status','clientName','clientCode','phone','delivery_man_id','address','orderDetail'];
}
