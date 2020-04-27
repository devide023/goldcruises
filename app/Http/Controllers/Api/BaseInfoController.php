<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\FunCode;
use App\Models\Icon;
use App\Models\MenuType;
use App\Models\OrganizeType;
use App\Models\Routes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class BaseInfoController extends Controller
{
    //
    public function province(Request $request)
    {
        try
        {
            $query = City::query();
            $query->when(!is_null($request->id), function (Builder $q) use ($request)
            {
                return $q->where('id', $request->id);
            });
            $query->when(!is_null($request->pid), function (Builder $q) use ($request)
            {
                return $q->where('pid', $request->pid);
            });
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $query->paginate()
            ];

        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function freshroute(Request $request)
    {
        try
        {
            $routes = Route::getRoutes();
            $list = [];
            foreach ($routes as $route)
            {
                if (Str::startsWith($route->uri, 'api/'))
                {
                    array_push($list, ['url' => $route->uri]);
                    $cnt = Routes::where('route', $route->uri)->count();
                    if ($cnt == 0)
                    {
                        Routes::create([
                            'status'    => 1,
                            'route'     => $route->uri,
                            'adduserid' => Auth::id(),
                            'addtime'   => now(),
                            'note'      => $route->methods[0]
                        ]);
                    }
                }
            }
            if (count($list) == Routes::count())
            {
                return [
                    'code'   => 1,
                    'msg'    => 'ok',
                    'result' => Routes::all()
                ];
            } else
            {
                return $this->error();
            }

        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function routelist(Request $request)
    {
        try
        {
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => Routes::all()
            ];
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function menutypelist(Request $request)
    {
        try
        {
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => MenuType::all()
            ];
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function orgtypes(Request $request)
    {
        try
        {
           $types = OrganizeType::all();
           return [
             'code'=>1,
             'msg'=>'ok',
             'result'=>$types
           ];
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function icons(Request $request)
    {
        try
        {
            $query = Icon::query();
            $query->where('status', 1);
            $query->when(!is_null($request->name), function (Builder $q) use ($request)
            {
                return $q->where('name', 'like', '%' . $request->name . '%');
            });
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $query->get()
            ];
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function funcods(Request $request)
    {
        try
        {
            $query = FunCode::query();
            $query->where('status', 1);
            $query->when(!is_null($request->code), function (Builder $q) use ($request)
            {
                return $q->where('code', 'like', '%' . $request->code . '%');
            });
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $query->get()
            ];
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function addfuncode(Request $request)
    {
        try
        {
            $cnt = FunCode::where('code',$request->code)->count();
            if($cnt==0){
                $funcode = FunCode::create([
                    'code' => $request->code,
                    'name'=>$request->name,
                    'status' => 1,
                    'addtime' => now(),
                    'adduserid' => Auth::id()
                ]);
                if($funcode->id>0){
                    return $this->success();
                }else{
                    return $this->error();
                }
            }else{
                return [
                  'code'=>0,
                  'msg'=>'功能编码重复',
                ];
            }

        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function delfuncode(Request $request)
    {
        try
        {
           $ret = FunCode::where('code',$request->code)->delete();
           if($ret){
               return $this->success();
           }
           else{
               return $this->error();
           }
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }
}
