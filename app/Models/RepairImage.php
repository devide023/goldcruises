<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RepairImage
 *
 * @property int $id
 * @property int|null $repairid 维修单id
 * @property string|null $filename 文件名
 * @property string|null $filetype 文件类型
 * @property string|null $originalname 原文件名
 * @property string|null $addtime 上传时间
 * @property int|null $adduserid 操作人
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairImage whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairImage whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairImage whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairImage whereFiletype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairImage whereOriginalname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairImage whereRepairid($value)
 * @mixin \Eloquent
 */
class RepairImage extends Model
{
    //
    protected $table = 'repairimage';
    public $timestamps = false;
    protected $guarded = [];
    protected $with = [];

    public function repair(){
        return $this->belongsTo('App\Models\Repair','repairid','id');
    }

}
