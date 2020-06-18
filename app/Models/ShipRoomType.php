<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ShipRoomType
 *
 * @property int $id
 * @property int|null $roomtypeid 房型id
 * @property string|null $shipno 邮轮编号
 * @property float|null $price 单价
 * @property int|null $addusrid 操作人
 * @property string|null $addtime 操作日期
 * @property-read \App\Models\User $addusername
 * @property-read \App\Models\RoomType|null $roomtypename
 * @property-read \App\Models\Ship|null $shipname
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShipRoomType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShipRoomType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShipRoomType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShipRoomType whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShipRoomType whereAddusrid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShipRoomType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShipRoomType wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShipRoomType whereRoomtypeid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShipRoomType whereShipno($value)
 * @mixin \Eloquent
 */
class ShipRoomType extends Model
{
    //
    protected $table='shiproomtype';
    protected $guarded=[];
    public $timestamps=false;
    protected $with=['roomtypename','shipname','addusername'];

    public function roomtypename(){
        return $this->belongsTo('App\Models\RoomType','roomtypeid','id');
    }
    public function shipname(){
        return $this->belongsTo('App\Models\Ship','shipno','code');
    }
    public function addusername(){
        return $this->belongsTo('App\Models\User','adduserid','id');
    }
}
