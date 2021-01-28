<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Financials extends Model
{
    public $table = 'financial';
    protected $fillable = ['refdebit', 'debit', 'refcredit', 'credit', 'description', 'created_by', 'updated_by', 'deleted_by'];
    use SoftDeletes;
}
