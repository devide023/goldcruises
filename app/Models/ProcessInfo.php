<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProcessInfo
 *
 * @property int $id
 * @property int|null $processid 流程id
 * @property int|null $billid 单据id
 * @property int|null $stepno 当前步骤
 * @property int|null $flow 流程流向
 * @property int|null $adduserid 操作人
 * @property string|null $addtime 操作日期
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProcessInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProcessInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProcessInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProcessInfo whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProcessInfo whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProcessInfo whereBillid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProcessInfo whereFlow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProcessInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProcessInfo whereProcessid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProcessInfo whereStepno($value)
 * @mixin \Eloquent
 */
class ProcessInfo extends Model
{
    //
    protected $table='processinfo';
    public $timestamps=false;
    protected $guarded=[];
    protected $with=[];
}
