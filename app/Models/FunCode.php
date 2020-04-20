<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FunCode
 *
 * @property int $id
 * @property int|null $status 状态
 * @property string|null $code 功能编码
 * @property string|null $name 功能名称
 * @property string $addtime 操作日期
 * @property int|null $adduserid 操作人
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FunCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FunCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FunCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FunCode whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FunCode whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FunCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FunCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FunCode whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FunCode whereStatus($value)
 * @mixin \Eloquent
 */
class FunCode extends Model
{
    //
    protected $table='funcode';
    protected $guarded=[];
    public $timestamps=false;
    protected $casts=[];
    protected $with=['adduser:id,name'];

    public function adduser(){
        return $this->belongsTo('App\Models\User','adduserid','id');
    }
}
