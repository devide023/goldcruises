<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ContractType
 *
 * @property int $id
 * @property int|null $name 类型名称
 * @property int|null $code 类型编码
 * @property int|null $adduserid 操作员
 * @property string|null $addtime 操作日期
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractType whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractType whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractType whereName($value)
 * @mixin \Eloquent
 */
class ContractType extends Model
{
    //
    protected $table='contracttype';
    protected $guarded=[];
    public $timestamps=false;
}
