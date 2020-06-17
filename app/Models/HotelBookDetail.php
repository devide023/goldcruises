<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\HotelBookDetail
 *
 * @property int $id
 * @property int|null $bookid 预定id
 * @property int|null $roomtypeid 房型id
 * @property int|null $qty 预定数量
 * @property-read \App\Models\HotelBook|null $book
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotelBookDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotelBookDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotelBookDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotelBookDetail whereBookid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotelBookDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotelBookDetail whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotelBookDetail whereRoomtypeid($value)
 * @mixin \Eloquent
 */
class HotelBookDetail extends Model
{
    //
    protected $table='hotelbookdetail';
    protected $guarded=[];
    public $timestamps=false;
    protected $with=['roomtype'];

    public function book(){
        return $this->belongsTo('App\Models\HotelBook','bookid','id');
    }

    public function roomtype(){
        return $this->belongsTo('App\Models\RoomType','roomtypeid','id');
    }
}
