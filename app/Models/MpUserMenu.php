<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MpUserMenu
 *
 * @property int $id
 * @property int|null $userid 用户id
 * @property string|null $mpmenuid 功能id
 * @property-read \App\Models\MpMenu|null $menuname
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MpUserMenu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MpUserMenu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MpUserMenu query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MpUserMenu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MpUserMenu whereMpmenuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MpUserMenu whereUserid($value)
 * @mixin \Eloquent
 */
class MpUserMenu extends Model
{
    //
    protected $table = 'mpusermenu';
    protected $guarded = [];
    public $timestamps = false;
    protected $with = [
        'menuname'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'userid', 'id');
    }

    public function menuname()
    {
        return $this->belongsTo('App\Models\MpMenu', 'mpmenuid', 'id');
    }
}
