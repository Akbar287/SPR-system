<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class historyPeople extends Model
{
    public $table = 'historypeoples';
    protected $fillable = ['id_users', 'title', 'description', 'icon', 'ip_address'];
}
