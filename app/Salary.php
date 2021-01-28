<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    public $table = 'salary';
    protected $fillable = ['id_user', 'id_beban', 'total', 'status'];
}
