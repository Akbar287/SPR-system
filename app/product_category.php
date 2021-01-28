<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product_category extends Model
{
    public $table = 'product_category';
    protected $fillable = ['product_id', 'category_id'];
}
