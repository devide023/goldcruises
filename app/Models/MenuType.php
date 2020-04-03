<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MenuType
 *
 * @property int $id
 * @property int|null $status 状态
 * @property string|null $code 类型编码
 * @property string|null $name 类型名称
 * @property string|null $addtime 操作日期
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuType whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuType whereStatus($value)
 * @mixin \Eloquent
 */
class MenuType extends Model
{
    //
    protected $table='menutype';
    protected $guarded=[];
    public $timestamps=false;
    protected $casts=[];

}
