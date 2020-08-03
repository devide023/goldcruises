<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AccessToken
 *
 * @property int $id
 * @property string|null $access_token access_token
 * @property string|null $addtime 添加时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccessToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccessToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccessToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccessToken whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccessToken whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccessToken whereId($value)
 * @mixin \Eloquent
 */
class AccessToken extends Model
{
    //
    protected $table='accesstoken';
    public $timestamps=false;
    protected $guarded=[];
}
