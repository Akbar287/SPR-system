<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Beban extends Model
{
    public $table = 'beban';
    protected $fillable = ['refbeban', 'total', 'id_financial'];
    use SoftDeletes;
}
