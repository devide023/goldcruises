<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MpMenu
 *
 * @property int $id
 * @property string|null $name 名称
 * @property string|null $icon 图标名称
 * @property string|null $pagepath 小程序页面路径
 * @property string|null $addtime 录入日期
 * @property string|null $adduserid 操作员
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MpMenu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MpMenu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MpMenu query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MpMenu whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MpMenu whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MpMenu whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MpMenu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MpMenu whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MpMenu wherePagepath($value)
 * @mixin \Eloquent
 * @property int|null $size 图标大小
 * @property string|null $color 图标颜色
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MpMenu whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MpMenu whereSize($value)
 * @property string|null $fontsize 文字大小
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MpMenu whereFontsize($value)
 */
class MpMenu extends Model
{
    //
    protected $table='mpmenu';
    public $timestamps=false;
    protected $guarded=[];
    protected $with=[];
}
