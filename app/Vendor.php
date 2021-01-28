<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    public $table = 'vendor';
    protected $fillable = ['name', 'image', 'phone', 'address', 'is_active'];
    use SoftDeletes;
}
