<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ContractStatus
 *
 * @property int $id
 * @property int|null $name 状态名称
 * @property int|null $code 状态编码
 * @property int|null $adduserid 操作员
 * @property string|null $addtime 操作日期
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractStatus whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractStatus whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractStatus whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractStatus whereName($value)
 * @mixin \Eloquent
 */
class ContractStatus extends Model
{
    //
    protected $table='contractstatus';
    protected $guarded=[];
    public $timestamps=false;
}
