<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'order_date',
        'customer_name',
        'product_name',
        'quantity',
        'price',
        'total',
    ];
}
