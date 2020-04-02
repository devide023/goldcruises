<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Menu
 *
 * @property int $id
 * @property int $pid 父id
 * @property string $name 菜单名称
 * @property string $menucode 菜单编码
 * @property string $menutype 菜单类型
 * @property string $icon 图标
 * @property int $status 状态
 * @property int $adduserid 操作人
 * @property string $addtime 操作日期
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereMenucode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereMenutype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereStatus($value)
 * @mixin \Eloquent
 */
class Menu extends Model
{
    //
    protected $table='menu';
    public $timestamps=false;
    protected $guarded=[];
    protected $casts=[];

    public function roles(){
        return $this->belongsToMany('App\Models\Role','rolemenu','menuid','roleid');
    }
}
