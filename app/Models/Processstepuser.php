<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Processstepuser extends Model
{
    //
    protected $table='processstepuser';
    public $timestamps=false;
    protected $guarded=[];
    protected $with=[
        'step',
        'user'
    ];

    public function step()
    {
        return $this->belongsTo('App\Models\Processstep','processstepid','id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','userid','id');
    }

}
