<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RepairUsers
 *
 * @property int $id
 * @property int|null $repairid 维修id
 * @property int|null $adduserid 操作人
 * @property string|null $addtime 录入日期
 * @property-read \App\Models\Repair|null $repair
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairUsers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairUsers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairUsers query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairUsers whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairUsers whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairUsers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairUsers whereRepairid($value)
 * @mixin \Eloquent
 */
class RepairUsers extends Model
{
    //
    protected $table='repairusers';
    public $timestamps=false;
    protected $with=['user:id,name,tel'];
    protected $guarded=[];

    public function repair(){
        return $this->belongsTo('App\Models\Repair','repairid','id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User','adduserid','id');
    }
}
