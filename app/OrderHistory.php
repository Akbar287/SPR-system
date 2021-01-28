<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    public $table = 'history_order';
    protected $fillable = ['id_order', 'status', 'description'];
}
