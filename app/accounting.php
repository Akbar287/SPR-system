<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class accounting extends Model
{
    public $table = 'accounting';
    protected $fillable = ['ref', 'type', 'name'];
}
