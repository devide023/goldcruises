<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MealBookDetail
 *
 * @property int $id
 * @property int|null $bookid 预订id
 * @property int|null $mealid 套餐id
 * @property float|null $price 套餐价格
 * @property float|null $qty 数量
 * @property float|null $amount 金额
 * @property-read \App\Models\MealBook|null $bookinfo
 * @property-read \App\Models\Meal|null $mealname
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MealBookDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MealBookDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MealBookDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MealBookDetail whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MealBookDetail whereBookid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MealBookDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MealBookDetail whereMealid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MealBookDetail wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MealBookDetail whereQty($value)
 * @mixin \Eloquent
 */
class MealBookDetail extends Model
{
    //
    protected $table='mealbookdetail';
    protected $guarded=[];
    public $timestamps=false;
    protected $with=[];

    public function mealname(){
        return $this->belongsTo('App\Models\Meal','mealid','id');
    }

    public function bookinfo(){
        return $this->belongsTo('App\Models\MealBook','bookid','id');
    }
}
