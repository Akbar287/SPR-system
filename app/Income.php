<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Income extends Model
{
    public $table = 'income';
    protected $fillable = ['id_financial', 'price', 'discount', 'total', 'image',' invoice', 'created_by', 'updated_by', 'deleted_by'];
    use SoftDeletes;
}
