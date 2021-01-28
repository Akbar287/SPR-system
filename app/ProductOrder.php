<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    public $table = 'product_orders';
    protected $fillable = ['order_id', 'product_id', 'quantity', 'price', 'discount'];
}
