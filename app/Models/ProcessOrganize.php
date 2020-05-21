<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProcessOrganize
 *
 * @property int $id
 * @property int|null $processid 流程id
 * @property int|null $orgid 组织节点id
 * @property int|null $adduserid 操作人
 * @property string|null $addtime 操作日期
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProcessOrganize newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProcessOrganize newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProcessOrganize query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProcessOrganize whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProcessOrganize whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProcessOrganize whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProcessOrganize whereOrgid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProcessOrganize whereProcessid($value)
 * @mixin \Eloquent
 */
class ProcessOrganize extends Model
{
    //
    protected $table='processogranize';
    protected $guarded=[];
    public $timestamps=false;
    protected $with=[];
}
