<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Sql
 *
 * @property int $id
 * @property string|null $tsql sql语句
 * @property string|null $action 方法
 * @property string|null $controller 控制器
 * @property string|null $url 路径
 * @property string|null $note 备注
 * @property int|null $adduserid 操作人
 * @property string|null $addtime 操作日期
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Sqlparam[] $params
 * @property-read int|null $params_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sql newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sql newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sql query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sql whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sql whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sql whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sql whereController($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sql whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sql whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sql whereTsql($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sql whereUrl($value)
 * @mixin \Eloquent
 */
class Sql extends Model
{
    //
    protected $table = 'sql';
    protected $guarded = [];
    public $timestamps = false;
    protected $with = ['params'];

    public function params()
    {
        return $this->hasMany('App\Models\Sqlparam', 'sqlid', 'id');
    }
}
