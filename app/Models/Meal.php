<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Meal
 *
 * @property int $id
 * @property int|null $status 状态
 * @property string|null $name 套餐名称
 * @property float|null $price 单价
 * @property int|null $adduserid 操作人
 * @property string|null $addtime 操作日期
 * @property int|null $orgid 组织id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Meal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Meal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Meal query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Meal whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Meal whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Meal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Meal whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Meal whereOrgid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Meal wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Meal whereStatus($value)
 * @mixin \Eloquent
 */
class Meal extends Model
{
    //
    protected $table='meal';
    protected $guarded=[];
    public $timestamps=false;
    protected $with=['addusername:id,name'];

    public function addusername(){
        return $this->belongsTo('App\Models\User','adduserid','id');
    }
}
