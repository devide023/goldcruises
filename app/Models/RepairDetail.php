<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RepairDetail
 *
 * @property int $id
 * @property int|null $repairid 维修单id
 * @property string|null $content 处理意见
 * @property int|null $dealuserid 处理人
 * @property string|null $dealtime 处理时间
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RepairDetailImg[] $detailimages
 * @property-read int|null $detailimages_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairDetail whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairDetail whereDealtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairDetail whereDealuserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepairDetail whereRepairid($value)
 * @mixin \Eloquent
 */
class RepairDetail extends Model
{
    //
    protected $table = 'repairdetail';
    public $timestamps = false;
    protected $guarded = [];
    protected $with = [
        'detailimages'
    ];

    public function detailimages()
    {
        return $this->hasMany('App\Models\RepairDetailImg', 'detailid', 'id');
    }
}
