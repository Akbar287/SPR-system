<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Materials extends Model
{
    public $table = 'materials';
    protected $fillable = ['name', 'description', 'stock', 'image', 'is_active','is_rewrite', 'created_by', 'updated_by', 'deleted_by'];
    use SoftDeletes;
}
