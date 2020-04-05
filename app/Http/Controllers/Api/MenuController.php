<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    //

    public function list(Request $request)
    {
        try
        {
            $query = Menu::query();
            $query->when($request->pid, function (Builder $q) use ($request)
            {
                return $q->where('pid', $request->pid);
            });
            $query->when($request->name, function (Builder $q) use ($request)
            {
                return $q->where('name', 'like', '%' . $request->name . '%');
            });
            $query->when($request->menucode, function (Builder $q) use ($request)
            {
                return $q->where('menucode', $request->menucode);
            });
            $query->when($request->menutype, function (Builder $q) use ($request)
            {
                return $q->where('menutype', $request->menutype);
            });
            $query->when(!is_null($request->ksrq) && !is_null($request->jsrq), function (Builder $q) use ($request)
            {
                return $q->whereBetween('addtime', [
                    $request->ksrq . ' 00:00:00',
                    $request->jsrq . ' 23:59:59'
                ]);
            });
            return $query->paginate();
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function add(Request $request)
    {
        try
        {
            $menu = Menu::create([
                'pid'       => $request->pid,
                'name'      => $request->name,
                'menucode'  => $request->menucode,
                'menutype'  => $request->menutype,
                'icon'      => $request->icon,
                'adduserid' => Auth::user()->id,
                'addtime'   => now(),
                'status'    => $request->status
            ]);
            if ($menu->id > 0)
            {
                return $this->success();
            } else
            {
                return $this->error();
            }
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function edit(Request $request)
    {
        try
        {
            $menu = Menu::find($request->id);
            if (!is_null($menu))
            {
                $isok = $menu->update([
                    'status'   => $request->status,
                    'pid'      => $request->pid,
                    'name'     => $request->name,
                    'menucode' => $request->menucode,
                    'menutype' => $request->menutype,
                    'icon'     => $request->icon,
                ]);
                if ($isok)
                {
                    return $this->success();
                } else
                {
                    return $this->error();
                }
            } else
            {
                return [
                    'code' => 0,
                    'msg'  => '参数错误',
                ];
            }


        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function del(Request $request)
    {
        try
        {
           DB::transaction(function () use ($request){
               $menu = Menu::find($request->id);
               $menu->delete();
               $menu->roles()->detach();
           });
           return $this->success();
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function menuroles(Request $request)
    {
        try{
            $menu = Menu::find($request->id);
            $menu->roles()->sync($request->roleids);
            return $this->success();
        }catch (Exception $exception){

        }
    }

    public function getusers(Request $request)
    {
        try{
            $ids = DB::table('rolemenu')->where('rolemenu.menuid',$request->id)
                ->join('roleuser','roleuser.roleid','=','rolemenu.roleid')
                ->join('user','user.id','=','roleuser.userid')
                ->select('user.id');

            return User::whereIn('id',$ids)->get();
        }catch (Exception $exception){

        }
    }

}
