<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AgentPlaceDateDetail
 *
 * @property int $id
 * @property int|null $placedateid 控位日期id
 * @property int|null $roomtypeid 房型id
 * @property float|null $qty 位置数
 * @property-read \App\Models\RoomType|null $roomtype
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDateDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDateDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDateDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDateDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDateDetail wherePlacedateid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDateDetail whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDateDetail whereRoomtypeid($value)
 * @mixin \Eloquent
 */
class AgentPlaceDateDetail extends Model
{
    //
    protected $table='agentplace_date_detail';
    protected $guarded=[];
    public $timestamps=false;
    protected $with=['roomtype'];

    public function roomtype(){
        return $this->belongsTo('App\Models\RoomType','roomtypeid','id');
    }
}
