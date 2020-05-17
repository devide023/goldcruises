<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Processstepuser
 *
 * @property int $id
 * @property int|null $processstepid 步骤id
 * @property int|null $userid 用户id
 * @property-read \App\Models\Processstep|null $step
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Processstepuser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Processstepuser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Processstepuser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Processstepuser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Processstepuser whereProcessstepid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Processstepuser whereUserid($value)
 * @mixin \Eloquent
 */
class Processstepuser extends Model
{
    //
    protected $table='processstepuser';
    public $timestamps=false;
    protected $guarded=[];
    protected $with=[
        'user:id,name'
    ];

    public function step()
    {
        return $this->belongsTo('App\Models\Processstep','processstepid','id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','userid','id');
    }

}
