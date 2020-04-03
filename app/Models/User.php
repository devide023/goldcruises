<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\User
 *
 * @property int $id
 * @property int $status 状态
 * @property string $usercode 用户编码
 * @property string $name 姓名
 * @property string $userpwd 用户密码
 * @property int $sex 性别
 * @property string|null $birthdate 生日
 * @property string|null $idno 身份证号码
 * @property string|null $tel 电话
 * @property string|null $email 邮箱
 * @property string|null $adress 地址
 * @property string|null $headimg 头像文件名
 * @property string|null $api_token token
 * @property int|null $province 省
 * @property int|null $city 市
 * @property int|null $district 区
 * @property int $adduserid 操作人
 * @property string $addtime 录入时间
 * @property-read \App\Models\User $adduser
 * @property-read \App\Models\City|null $cityname
 * @property-read \App\Models\City|null $districtname
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Organize[] $orgnodes
 * @property-read int|null $orgnodes_count
 * @property-read \App\Models\City|null $provincename
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \App\Models\Sex $sexname
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereAdress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereBirthdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereHeadimg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIdno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereTel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUsercode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUserpwd($value)
 * @mixin \Eloquent
 */
class User extends \Illuminate\Foundation\Auth\User
{
    //
    protected $table = 'user';
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = [];
    protected $hidden = [
        'userpwd',
        'api_token'
    ];
    protected $with = [
        'sexname:code,name',
        'adduser:id,name',
        'provincename:code,name',
        'cityname:code,name',
        'districtname:code,name'
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'roleuser', 'userid', 'roleid');
    }

    public function orgnodes()
    {
        return $this->belongsToMany('App\Models\Organize', 'userorg', 'userid', 'orgid');
    }

    public function sexname()
    {
        return $this->belongsTo('App\Models\Sex', 'sex', 'code');
    }

    public function adduser()
    {
        return $this->belongsTo('App\Models\User', 'adduserid', 'id');
    }

    public function provincename()
    {
        return $this->belongsTo('App\Models\City', 'province', 'code');
    }

    public function cityname()
    {
        return $this->belongsTo('App\Models\City', 'city', 'code');
    }

    public function districtname()
    {
        return $this->belongsTo('App\Models\City', 'district', 'code');
    }
}
