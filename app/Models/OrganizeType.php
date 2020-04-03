<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrganizeType
 *
 * @property int $id
 * @property int|null $status
 * @property string|null $code 编码
 * @property string|null $name 名称
 * @property string $addtime 操作日期
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrganizeType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrganizeType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrganizeType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrganizeType whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrganizeType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrganizeType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrganizeType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrganizeType whereStatus($value)
 * @mixin \Eloquent
 */
class OrganizeType extends Model
{
    //
    protected $table='organizetype';
    public $timestamps=false;
    protected $guarded=[];
}
