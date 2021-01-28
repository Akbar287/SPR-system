<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    public $table = 'discount';
    protected $fillable = ['id_product', 'id_user', 'sale', 'created_by', 'updated_by'];
}
