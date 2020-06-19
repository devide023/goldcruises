<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\HotelBook
 *
 * @property int $id
 * @property string|null $bdate 预计入住日期
 * @property string|null $edate 预计退房日期
 * @property string|null $bookname 预订人姓名
 * @property int|null $bookcount 预订人人数
 * @property string|null $booktel 预订人电话
 * @property float|null $amount 预订费用
 * @property int|null $ispayed 是否已付费用
 * @property string|null $booknote 预订备注
 * @property int|null $adduserid 操作人id
 * @property string|null $addtime 操作日期
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\HotelBookDetail[] $details
 * @property-read int|null $details_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotelBook newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotelBook newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotelBook query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotelBook whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotelBook whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotelBook whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotelBook whereBdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotelBook whereBookcount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotelBook whereBookname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotelBook whereBooknote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotelBook whereBooktel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotelBook whereEdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotelBook whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotelBook whereIspayed($value)
 * @mixin \Eloquent
 */
class HotelBook extends Model
{
    //
    protected $table='hotelbook';
    protected $guarded=[];
    public $timestamps=false;
    protected $with=['shipname','details','addusername:id,name','statusname:statusid,name'];

    public function statusname(){
        return $this->belongsTo('App\Models\BookStatus','status','statusid');
    }

    public function details(){
        return $this->hasMany('App\Models\HotelBookDetail','bookid','id');
    }

    public function shipname(){
        return $this->belongsTo('App\Models\Ship','shipno','code');
    }

    public function addusername(){
        return $this->belongsTo('App\Models\User','adduserid','id');
    }
}
