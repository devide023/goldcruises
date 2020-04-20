<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Icon
 *
 * @property int $id
 * @property int|null $status 状态
 * @property string|null $name 图标名称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Icon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Icon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Icon query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Icon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Icon whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Icon whereStatus($value)
 * @mixin \Eloquent
 */
class Icon extends Model
{
    //
    protected $table='icon';
    protected $guarded=[];
    public $timestamps=false;
    protected $casts=[];
}
