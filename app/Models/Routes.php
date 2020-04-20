<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Routes
 *
 * @property int $id
 * @property int|null $status
 * @property string|null $route 路由
 * @property string|null $group 分组
 * @property string|null $note 备注
 * @property int|null $adduserid 操作员
 * @property string|null $addtime 操作日期
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Routes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Routes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Routes query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Routes whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Routes whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Routes whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Routes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Routes whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Routes whereRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Routes whereStatus($value)
 * @mixin \Eloquent
 */
class Routes extends Model
{
    //
    protected $table='route';
    public $timestamps=false;
    protected $guarded=[];
    protected $casts=[];
    protected $with=['adduser:id,name'];
    public function adduser(){
        return $this->belongsTo('App\Models\User','adduserid','id');
    }
}
