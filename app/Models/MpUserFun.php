<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MpUserFun
 *
 * @property int $id
 * @property int|null $userid 用户id
 * @property string|null $funid 功能id
 * @property-read \App\Models\FunCode|null $funname
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MpUserFun newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MpUserFun newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MpUserFun query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MpUserFun whereFunid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MpUserFun whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MpUserFun whereUserid($value)
 * @mixin \Eloquent
 */
class MpUserFun extends Model
{
    //
    protected $table='mpuserfun';
    protected $guarded=[];
    public $timestamps=false;
    protected $with=[
        'funname'
    ];
    public function user(){
        return $this->belongsTo('App\Models\User','userid','id');
    }

    public function funname(){
        return $this->belongsTo('App\Models\FunCode','funid','id');
    }
}
