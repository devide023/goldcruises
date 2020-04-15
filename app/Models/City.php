<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\City
 *
 * @property int $id
 * @property int|null $pid 父id
 * @property string|null $name 名称
 * @property int|null $status 状态
 * @property string|null $code 地区编码
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereStatus($value)
 * @mixin \Eloquent
 */
class City extends Model
{
    //
    protected $table='city';
    protected $guarded=[];
    public $timestamps=false;
    protected $casts=[];
    protected $perPage=900;
}
