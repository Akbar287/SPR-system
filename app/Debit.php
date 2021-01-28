<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Debit extends Model
{
    public $table = 'debit';
    protected $fillable = ['refdebit', 'debitdesc'];
}
