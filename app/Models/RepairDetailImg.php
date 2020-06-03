<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RepairDetailImg
 *
 * @property int $id
 * @property int|null $detailid 维修详情id
 * @property string|null $filename 文件名
 * @property string|null $filetype 文件类型
 * @property string|null $originalname 原文件名
 * @property string|null $addtime 上传时间
 * @property int|null $adduserid 操作人
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairDetailImg newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairDetailImg newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairDetailImg query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairDetailImg whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairDetailImg whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairDetailImg whereDetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairDetailImg whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairDetailImg whereFiletype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairDetailImg whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairDetailImg whereOriginalname($value)
 * @mixin \Eloquent
 */
class RepairDetailImg extends Model
{
    //
    protected $table = 'repairdetailimg';
    public $timestamps = false;
    protected $guarded = [];
    protected $with = [];
    public function repairdetail(){
        return $this->belongsTo('App\Models\RepairDetail','detailid','id');
    }
}
