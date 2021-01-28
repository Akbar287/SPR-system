<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryProducts extends Model
{
    public $table = 'categories';
    protected $fillable = ['name', 'description', 'image', 'created_by'];
    use SoftDeletes;
}
