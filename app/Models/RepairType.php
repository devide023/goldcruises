<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RepairType
 *
 * @property int $id
 * @property string|null $code 编码
 * @property string|null $name 名称
 * @property int|null $seq 序号
 * @property int|null $adduserid 录入人
 * @property string|null $addtime 操作日期
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairType whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairType whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairType whereSeq($value)
 * @mixin \Eloquent
 */
class RepairType extends Model
{
    //
    protected $table="repairtype";
    protected $guarded=[];
    public $timestamps=false;
    protected $with=[];
}
