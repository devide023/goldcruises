<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\BusProcess
 *
 * @property int $id
 * @property int|null $status 状态
 * @property string|null $name 流程名称
 * @property int|null $adduserid 操作人
 * @property int|null $orgid 组织节点id
 * @property string|null $addtime 操作时间
 * @property-read \App\Models\User|null $adduser
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Processstep[] $steps
 * @property-read int|null $steps_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BusProcess newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BusProcess newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BusProcess query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BusProcess whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BusProcess whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BusProcess whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BusProcess whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BusProcess whereOrgid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BusProcess whereStatus($value)
 * @mixin \Eloquent
 */
class BusProcess extends Model
{
    //
    protected $table="process";
    protected $guarded=[];
    public $timestamps=false;
    protected $with=[
        'adduser:id,name',
        'steps',
        'processorgs'
    ];

    public function adduser(){
        return $this->belongsTo('App\Models\User','adduserid','id');
    }

    public function steps()
    {
        return $this->hasMany('App\Models\Processstep','processid','id');
    }

    public function processorgs(){
        return $this->hasMany('App\Models\ProcessOrganize','processid','id');
    }
}
