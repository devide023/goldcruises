<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserPermission
 *
 * @property int $id
 * @property int|null $userid 用户id
 * @property int|null $orgid 组织节点
 * @property int|null $adduserid
 * @property string|null $addtime
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPermission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPermission whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPermission whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPermission whereOrgid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPermission whereUserid($value)
 * @mixin \Eloquent
 */
class UserPermission extends Model
{
    //
    protected $table='userpermission';
    public $timestamps=false;
    protected $guarded=[];
    protected $with=[];
}
