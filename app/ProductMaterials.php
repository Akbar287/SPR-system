<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductMaterials extends Model
{
    public $table = 'material_product';
    protected $fillable = ['id_product', 'id_material', 'unit'];
}
