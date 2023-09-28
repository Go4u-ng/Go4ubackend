<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'food_name',
        'customer_name',
        'customer_phone',
        'customer_address',
        'customer_id',
        'tracking_id',
        'status',
        'payment_method',
        'payment_status',
        'total_price',
    ];
}
