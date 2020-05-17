<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    //
    protected $table="process";
    protected $guarded=[];
    public $timestamps=false;
    protected $with=[
        'adduser',
        'steps'
    ];

    public function adduser(){
        return $this->belongsTo('App\Models\User','adduserid','id');
    }

    public function steps()
    {
        return $this->hasMany('App\Models\Processstep','processid','id');
    }
}
