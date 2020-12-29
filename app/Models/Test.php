<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Test
 *
 * @property int $id
 * @property string|null $hcbh 航次编号
 * @property string|null $name 客人姓名
 * @property string|null $certno 证件编号
 * @property int|null $fromdb 系统数据库
 * @property string|null $addtime 录入日期
 * @method static \Illuminate\Database\Eloquent\Builder|Test newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Test newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Test query()
 * @method static \Illuminate\Database\Eloquent\Builder|Test whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Test whereCertno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Test whereFromdb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Test whereHcbh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Test whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Test whereName($value)
 * @mixin \Eloquent
 */
class Test extends Model
{
    //
    protected $table='test';
    public $timestamps=false;
    protected $guarded=[];
    protected $with=[];
}
