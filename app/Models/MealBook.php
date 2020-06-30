<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MealBook
 *
 * @property int $id
 * @property int|null $status 状态
 * @property string|null $bookname 预订人
 * @property string|null $booktel 预订电话
 * @property float|null $amount 费用
 * @property int|null $ispayed 付款标志
 * @property string|null $booknote 预订备注
 * @property int|null $adduserid 操作人
 * @property string|null $addtime 操作日期
 * @property int|null $orgid 组织id
 * @property-read \App\Models\User|null $addusername
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MealBookDetail[] $details
 * @property-read int|null $details_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MealBook newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MealBook newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MealBook query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MealBook whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MealBook whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MealBook whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MealBook whereBookname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MealBook whereBooknote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MealBook whereBooktel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MealBook whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MealBook whereIspayed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MealBook whereOrgid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MealBook whereStatus($value)
 * @mixin \Eloquent
 */
class MealBook extends Model
{
    //
    protected $table='mealbook';
    protected $guarded=[];
    public $timestamps=false;
    protected $with=['details','addusername:id,name','shipname:code,name','statusname:statusid,name'];

    public function details(){
        return $this->hasMany('App\Models\MealBookDetail','bookid','id');
    }

    public function addusername(){
        return $this->belongsTo('App\Models\User','adduserid','id');
    }
    public function statusname(){
        return $this->belongsTo('App\Models\BookStatus','status','statusid');
    }
    public function shipname(){
        return $this->belongsTo('App\Models\Ship','shipno','code');
    }
}
