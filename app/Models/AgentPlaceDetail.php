<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AgentPlaceDetail
 *
 * @property int $id
 * @property int|null $agentplaceid 控位id
 * @property int|null $roomtypeid 房型id
 * @property float|null $qty 控位数量
 * @property-read \App\Models\RoomType|null $roomtype
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDetail whereAgentplaceid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDetail whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDetail whereRoomtypeid($value)
 * @mixin \Eloquent
 */
class AgentPlaceDetail extends Model
{
    //
    protected $table='agentplacedetail';
    protected $guarded=[];
    public $timestamps=false;
    protected $with=['roomtype'];

    public function roomtype(){
        return $this->belongsTo('App\Models\RoomType','roomtypeid','id');
    }
}
