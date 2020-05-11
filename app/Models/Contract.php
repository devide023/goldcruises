<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Contract
 *
 * @property int $id
 * @property string|null $status 合同状态
 * @property string|null $contractno 合同编号
 * @property string|null $name 合同名称
 * @property string|null $type 合同类型
 * @property string|null $cntidentity 我方身份
 * @property float|null $amount 合同金额
 * @property float|null $payedamount 已付金额
 * @property string|null $payway 支付方式
 * @property string|null $ contractcompany 承办单位
 * @property string|null $ contractor 承办人
 * @property string|null $ contractortel 承办人系方式
 * @property string|null $ signdate 签订日期
 * @property string|null $ bdate 合同开始日期
 * @property string|null $ edate 合同结束日期
 * @property string|null $ moneyflow 资金流向
 * @property string|null $ dutyperson 合同负责人
 * @property string|null $dutypersontel 合同负责人电话
 * @property int|null $ adduserid 操作人
 * @property string|null $ addtime 操作日期
 * @property-read \App\Models\User $adduser
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ContractFile[] $contractfiles
 * @property-read int|null $contractfiles_count
 * @property-read \App\Models\ContractStatus|null $statusname
 * @property-read \App\Models\ContractType|null $typename
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereBdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereCntidentity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereContractcompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereContractno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereContractor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereContractortel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereDutyperson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereDutypersontel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereEdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereMoneyflow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract wherePayedamount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract wherePayway($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereSigndate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Contract whereType($value)
 * @mixin \Eloquent
 */
class Contract extends Model
{
    //
    protected $table = 'contract';
    protected $guarded = [];
    public $timestamps = false;
    protected $casts = [];
    protected $with = [
        'adduser:id,name',
        'contractfiles',
        'contractstatus:code,name',
        'contraccttype:code,name'
    ];

    public function adduser()
    {
        return $this->belongsTo('App\Models\User', 'adduserid', 'id');
    }

    public function typename()
    {
        return $this->belongsTo('App\Models\ContractType', 'type', 'code');
    }

    public function statusname()
    {
        return $this->belongsTo('App\Models\ContractStatus', 'status', 'code');
    }

    public function contractstatus()
    {
        return $this->belongsTo('App\Models\ContractStatus', 'status', 'code');
    }

    public function contraccttype()
    {
        return $this->belongsTo('App\Models\ContractType', 'type', 'code');
    }

    public function contractfiles()
    {
        return $this->hasMany('App\Models\ContractFile', 'contractid');
    }
}
