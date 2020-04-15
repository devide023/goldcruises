<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BaseInfoController extends Controller
{
    //
    public function province(Request $request)
    {
        try
        {
            $query = City::query();
            $query->when(!is_null($request->id),function (Builder $q) use ($request){
                return $q->where('id',$request->id);
            });
            $query->when(!is_null($request->pid),function (Builder $q) use ($request){
                return $q->where('pid',$request->pid);
            });
            return [
                'code'=>1,
                'msg'=>'ok',
                'result'=>$query->paginate()
            ];

        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }
}
