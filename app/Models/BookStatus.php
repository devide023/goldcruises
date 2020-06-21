<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BookStatus
 *
 * @property int $id
 * @property int|null $statusid 状态id
 * @property string|null $name 状态名称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BookStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BookStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BookStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BookStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BookStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BookStatus whereStatusid($value)
 * @mixin \Eloquent
 */
class BookStatus extends Model
{
    //
    protected $table='bookstatus';
    protected $guarded=[];
    public $timestamps=false;

}
