<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderFinance extends Model
{
    public $table = 'order_finance';
    protected $fillable = ['id_order', 'id_financial'];
}
