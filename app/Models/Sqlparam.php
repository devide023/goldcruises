<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Sqlparam
 *
 * @property int $id
 * @property int|null $sqlid sql语句id
 * @property string|null $paramname 参数名称
 * @property string|null $paramtype 参数类型
 * @property-read \App\Models\Sql|null $sql
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sqlparam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sqlparam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sqlparam query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sqlparam whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sqlparam whereParamname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sqlparam whereParamtype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sqlparam whereSqlid($value)
 * @mixin \Eloquent
 */
class Sqlparam extends Model
{
    //
    protected $table = 'sqlparam';
    protected $guarded = [];
    public $timestamps = false;
    protected $with = [];

    public function sql()
    {
        return $this->belongsTo('App\Models\Sql', 'sqlid', 'id');
    }
}
