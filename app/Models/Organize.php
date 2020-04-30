<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Organize
 *
 * @property int $id
 * @property int|null $pid 父id
 * @property string|null $name 组织节点名称
 * @property string|null $orgcode 节点编码
 * @property int|null $status 状态
 * @property int|null $orgtype 节点类型
 * @property int|null $leader 领导人/法人
 * @property string|null $logo 节点标志
 * @property int|null $adduserid 操作人
 * @property string|null $addtime 操作时间
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organize newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organize newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organize query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organize whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organize whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organize whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organize whereLeader($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organize whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organize whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organize whereOrgcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organize whereOrgtype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organize wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organize whereStatus($value)
 * @mixin \Eloquent
 */
class Organize extends Model
{
    //
    protected $table='organize';
    protected $guarded=[];
    public $timestamps=false;
    protected $casts=[];
    protected $with=[
      'orgtypename:code,name',
    ];
    public function users(){
        return $this->belongsToMany('App\Models\User','userorg','departmentid','userid');
    }
    public function orgtypename(){
        return $this->belongsTo('App\Models\OrganizeType','orgtype','code');
    }
}
