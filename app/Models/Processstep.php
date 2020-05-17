<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Processstep extends Model
{
    //
    protected $table='processstep';
    public $timestamps=false;
    protected $guarded=[];
    protected $with=[
        'stepusers',
        'process'
    ];

    public function process(){
        return $this->belongsTo('App\Models\Process','processid','id');
    }

    public function stepusers(){
        return $this->hasMany('App\Models\Processstepuser','processstepid','id');
    }
}
