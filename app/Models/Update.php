<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Update
 *
 * @property int $id
 * @property string|null $appid 应用id
 * @property string|null $apkfilename apk包名
 * @property string|null $ipkfilename ipk包名
 * @property string|null $version 版本号
 * @property string|null $addtime
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Update newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Update newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Update query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Update whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Update whereApkfilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Update whereAppid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Update whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Update whereIpkfilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Update whereVersion($value)
 * @mixin \Eloquent
 */
class Update extends Model
{
    //
    protected $table='update';
    protected $guarded=[];
    public $timestamps=false;
    protected $with=[];
}
