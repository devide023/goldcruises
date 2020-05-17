<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Processstep
 *
 * @property int $id
 * @property int|null $processid 流程id
 * @property int|null $stepno 流程步骤
 * @property string|null $stepname 步骤名称
 * @property string|null $addtime 操作时间
 * @property-read \App\Models\BusProcess|null $busprocess
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Processstepuser[] $stepusers
 * @property-read int|null $stepusers_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Processstep newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Processstep newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Processstep query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Processstep whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Processstep whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Processstep whereProcessid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Processstep whereStepname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Processstep whereStepno($value)
 * @mixin \Eloquent
 */
class Processstep extends Model
{
    //
    protected $table='processstep';
    public $timestamps=false;
    protected $guarded=[];
    protected $with=[
        'stepusers'
    ];

    public function busprocess(){
        return $this->belongsTo('App\Models\BusProcess','processid','id');
    }

    public function stepusers(){
        return $this->hasMany('App\Models\Processstepuser','processstepid','id');
    }
}
