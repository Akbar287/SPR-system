<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Outcome extends Model
{
    public $table = 'outcome';
    protected $fillable = [ 'id_financial', 'price', 'discount', 'total', 'image', 'invoice', 'created_by', 'updated_by', 'deleted_by'];
    use SoftDeletes;
}
