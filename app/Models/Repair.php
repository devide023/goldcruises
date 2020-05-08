<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Repair
 *
 * @property int $id
 * @property string|null $status 状态
 * @property string|null $repairno 维修单号
 * @property string|null $title 维修主题
 * @property string|null $content 报修说明
 * @property int|null $adduserid 报修人id
 * @property string|null $adduser 报修人
 * @property string|null $addusertel 报修人电话
 * @property string|null $addtime 报修时间
 * @property int|null $dealuserid 处理人id
 * @property string|null $dealperson 处理人
 * @property string|null $dealpersontel 处理人电话
 * @property string|null $sendtime 派单时间
 * @property int|null $senduserid 派单人id
 * @property string|null $sendperson 派单人
 * @property string|null $endtime 完结日期
 * @property int|null $enduserid 完结操作人id
 * @property string|null $enduser 完结操作人
 * @property string|null $note 备注
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RepairDetail[] $details
 * @property-read int|null $details_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RepairImage[] $images
 * @property-read int|null $images_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Repair newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Repair newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Repair query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Repair whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Repair whereAdduser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Repair whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Repair whereAddusertel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Repair whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Repair whereDealperson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Repair whereDealpersontel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Repair whereDealuserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Repair whereEndtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Repair whereEnduser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Repair whereEnduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Repair whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Repair whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Repair whereRepairno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Repair whereSendperson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Repair whereSendtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Repair whereSenduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Repair whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Repair whereTitle($value)
 * @mixin \Eloquent
 */
class Repair extends Model
{
    //
    protected $table = 'repair';
    public $timestamps = false;
    protected $guarded = [];
    protected $with = [
        'details',
        'images'
    ];

    public function details()
    {
        return $this->hasMany('App\Models\RepairDetail', 'repairid', 'id');
    }

    public function images()
    {
        return $this->hasMany('App\Models\RepairImage', 'repairid', 'id');
    }
}
