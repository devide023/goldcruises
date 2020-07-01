<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AgentPlaceDate
 *
 * @property int $id
 * @property int|null $status 状态
 * @property string|null $bdate 开始日期
 * @property string|null $edate 结束日期
 * @property string|null $shipno 邮轮编号
 * @property int|null $agentid 代理商id
 * @property int|null $adduserid 操作人
 * @property string|null $addtime 操作日期
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AgentPlaceDateDetail[] $details
 * @property-read int|null $details_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDate query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDate whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDate whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDate whereAgentid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDate whereBdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDate whereEdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDate whereShipno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgentPlaceDate whereStatus($value)
 * @mixin \Eloquent
 */
class AgentPlaceDate extends Model
{
    //
    protected $table='agentplace_date';
    protected $guarded=[];
    public $timestamps=false;
    protected $with=['details','agent:id,name'];

    public function agent(){
        return $this->belongsTo('App\Models\Organize','agentid','id');
    }
    public  function details(){
        return $this->hasMany('App\Models\AgentPlaceDateDetail','placedateid','id');
    }
}
