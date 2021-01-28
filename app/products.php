<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class products extends Model
{
    public $table = 'products';
    protected $fillable = ['title', 'description', 'cover', 'price', 'stock'];
    use SoftDeletes;
}
