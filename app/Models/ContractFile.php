<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ContractFile
 *
 * @property int $id
 * @property int|null $status 状态
 * @property int|null $contractid 合同id
 * @property int|null $file 合同文件名
 * @property int|null $adduserid 操作员
 * @property string|null $addtime 操作日期
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractFile query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractFile whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractFile whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractFile whereContractid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractFile whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ContractFile whereStatus($value)
 * @mixin \Eloquent
 */
class ContractFile extends Model
{
    //
    protected $table='contractfiles';
    protected $guarded=[];
    public $timestamps=false;
    protected $casts=[];
    protected $with=['adduser:id,name'];

    public function adduser(){
        return $this->belongsTo('App\Models\User','adduserid','id');
    }
}
