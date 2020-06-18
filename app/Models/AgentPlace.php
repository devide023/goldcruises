<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AgentPlace
 *
 * @property int $id
 * @property int|null $status 状态
 * @property string|null $shipno 邮轮编号
 * @property int|null $agentid 代理商id
 * @property \App\Models\Organize|null $agentname 代理商名称
 * @property int|null $adduserid 操作人
 * @property string|null $addtime 操作日期
 * @property-read \App\Models\User|null $addusername
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AgentPlaceDetail[] $details
 * @property-read int|null $details_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlace newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlace newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlace query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlace whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlace whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlace whereAgentid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlace whereAgentname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlace whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlace whereShipno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlace whereStatus($value)
 * @mixin \Eloquent
 */
class AgentPlace extends Model
{
    //
    protected $table='agentplace';
    protected $guarded=[];
    public $timestamps=false;
    protected $with=['details','agentname:id,name','addusername:id,name'];

    public function details(){
        return $this->hasMany('App\Models\AgentPlaceDetail','agentplaceid','id');
    }

    public function agentname(){
        return $this->belongsTo('App\Models\Organize','agentid','id');
    }

    public function addusername(){
        return $this->belongsTo('App\Models\User','adduserid','id');
    }
}
