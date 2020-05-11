<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RepairStatus
 *
 * @property int $id
 * @property string|null $code 编码
 * @property string|null $name 名称
 * @property int|null $seq 序号
 * @property int|null $adduserid 录入人
 * @property string|null $addtime 操作日期
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairStatus whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairStatus whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairStatus whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairStatus whereSeq($value)
 * @mixin \Eloquent
 */
class RepairStatus extends Model
{
    //
    protected $table="repairstatus";
    protected $guarded=[];
    public $timestamps=false;
    protected $with=[];
}
