<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Sex
 *
 * @property int $id
 * @property int|null $status 状态
 * @property int|null $code 性别编码
 * @property string|null $name 名称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sex newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sex newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sex query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sex whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sex whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sex whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sex whereStatus($value)
 * @mixin \Eloquent
 */
class Sex extends Model
{
    //
    protected $table='sex';
    protected $guarded=[];
    public $timestamps=false;
    protected $casts=[];
}
