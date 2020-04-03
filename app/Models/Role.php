<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Role
 *
 * @property int $id
 * @property int $status 状态
 * @property string $name 角色名称
 * @property string|null $note 备注
 * @property int $adduserid 操作人
 * @property string $addtime 操作日期
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Menu[] $menus
 * @property-read int|null $menus_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereStatus($value)
 * @mixin \Eloquent
 */
class Role extends Model
{
    //
    protected $table='role';
    public $timestamps=false;
    protected $guarded=[];
    protected $casts=[];
    protected $with = [
      'adduser:id,name',
    ];
    public function users(){
        return $this->belongsToMany('App\Models\User','roleuser','roleid','userid');
    }
    public function adduser(){
        return $this->belongsTo('App\Models\User','adduserid','id');
    }
    public function menus(){
        return $this->belongsToMany('App\Models\Menu','rolemenu','roleid','menuid');
    }

}
