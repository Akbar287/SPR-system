<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductHPP extends Model
{
    public $table = 'product_hpp';
    protected $fillable = ['id_product', 'hpp'];
}
