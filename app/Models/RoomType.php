<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



/**
 * App\Models\RoomType
 *
 * @property int $id
 * @property int|null $status 状态
 * @property string|null $shipno 邮轮编号
 * @property string|null $name 房型名称
 * @property float|null $price 单价
 * @property float|null $totalqty 总房间数
 * @property int|null $adduserid 操作人id
 * @property string|null $addtime 操作日期
 * @property-read \App\Models\Ship|null $shipname
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RoomType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RoomType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RoomType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RoomType whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RoomType whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RoomType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RoomType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RoomType wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RoomType whereShipno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RoomType whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RoomType whereTotalqty($value)
 * @mixin \Eloquent
 */
class RoomType extends Model
{
    //
    protected $table='roomtype';
    protected $guarded=[];
    public $timestamps=false;
    protected $with=['shipname'];

    public function shipname(){
        return $this->belongsTo('App\Models\Ship','shipno','code');
    }
}
