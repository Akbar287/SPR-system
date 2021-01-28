<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class markets extends Model
{
    use SoftDeletes;
    public $table = 'markets';
    protected $fillable = ['name', 'owner', 'address', 'description', 'created_by', 'updated_by', 'deleted_by'];
}
