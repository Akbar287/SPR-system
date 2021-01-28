<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    public $table = 'purchase';
    protected $fillable = ['id_vendor', 'id_financial', 'id_material', 'stock', 'price', 'discount', 'total', 'image', 'invoice', 'created_by', 'updated_by', 'deleted_by'];
    use SoftDeletes;
}
