<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialUnit extends Model
{
    public $table = 'material_unit';
    protected $fillable = ['id_material', 'unit'];
}
