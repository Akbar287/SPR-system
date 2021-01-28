<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    public $table = 'orders';
    protected $fillable = ['id_users', 'id_markets', 'id_financial', 'purchase', 'metode', 'total_price', 'invoice', 'status'];
}
