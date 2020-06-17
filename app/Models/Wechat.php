<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Wechat
 *
 * @property int $id
 * @property string|null $openid
 * @property string|null $nickname
 * @property string|null $avatarUrl
 * @property string|null $city
 * @property string|null $province
 * @property int|null $gender
 * @property int|null $userid
 * @property string|null $addtime
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wechat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wechat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wechat query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wechat whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wechat whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wechat whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wechat whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wechat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wechat whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wechat whereOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wechat whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wechat whereUserid($value)
 * @mixin \Eloquent
 */
class Wechat extends Model
{
    //
    protected $table='wechat';
    protected $guarded=[];
    public $timestamps=false;
}
