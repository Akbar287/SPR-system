<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class resellerMarket extends Model
{
    public $table = 'reseller_market';
    protected $fillable = ['id_reseller', 'id_market', 'created_by', 'updated_by'];
}
