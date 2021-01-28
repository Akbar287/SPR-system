<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    public $table = 'credit';
    protected $fillable = ['refcredit, creditdesc'];
}
