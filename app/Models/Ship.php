<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Ship
 *
 * @property int $id
 * @property int|null $status 状态
 * @property string|null $code 邮轮编号
 * @property string|null $name 邮轮名称
 * @property int|null $adduserid 操作人
 * @property string|null $addtime 操作日期
 * @property-read \App\Models\User|null $addusername
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ship newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ship newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ship query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ship whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ship whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ship whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ship whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ship whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ship whereStatus($value)
 * @mixin \Eloquent
 */
class Ship extends Model
{
    //
    protected $table='ship';
    protected $guarded=[];
    public $timestamps=false;
    protected $with=['addusername:id,name'];

    public function addusername(){
        return $this->belongsTo('App\Models\User','adduserid','id');
    }
}
