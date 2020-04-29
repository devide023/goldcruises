<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    //

    public function list(Request $request)
    {
        try
        {
            $pagesize= $request->pagesize??15;
            $query = Role::query();
            $query->when($request->name, function (Builder $q) use ($request)
            {
                return $q->where('name', 'like', '%' . $request->name . '%');
            });
            $query->when(!is_null($request->ksrq) && !is_null($request->jsrq), function (Builder $q) use ($request)
            {
                return $q->whereBetween('addtime', [
                    $request->ksrq . ' 00:00:00',
                    $request->jsrq . ' 23:59:59'
                ]);
            });
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $query->paginate($pagesize)
            ];

        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function add(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $role = Role::create([
                'status'    => $request->status,
                'name'      => $request->name,
                'adduserid' => Auth::user()->id,
                'note'      => $request->note,
                'addtime'   => now()
            ]);
            $menuids = collect($request->menuids)->collapse()->unique();
            $role->menus()->sync($menuids);
            $role->users()->sync($request->userids);
            $role->routes()->sync($request->routeids);
            if ($role->id > 0)
            {
                DB::commit();
                return $this->success();
            } else
            {
                DB::rollBack();
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
            $roleid = $request->id;
            $role = Role::find($roleid);
            if (is_null($role))
            {
                return [
                    'code' => 0,
                    'msg'  => '参数不正确',
                ];
            } else
            {
                $menuids = collect($request->menuids)->collapse()->unique();
                DB::beginTransaction();
                $role->menus()->sync($menuids);
                $role->routes()->sync($request->routeids);
                $role->users()->sync($request->userids);
                $isok = $role->update([
                    'status' => $request->status,
                    'note'   => $request->note,
                    'name'   => $request->name
                ]);
                if ($isok)
                {
                    DB::commit();
                    return $this->success();
                } else
                {
                    DB::rollBack();
                    return $this->error();
                }
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
            DB::transaction(function () use ($request)
            {
                $role = Role::find($request->id);
                $role->users()->detach();
                $role->menus()->detach();
                $role->delete();
            });
            return $this->success();
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function roleuser(Request $request)
    {
        try
        {
            $roleid = $request->id;
            $role = Role::find($roleid);
            $role->users()->sync($request->userids);
            return $this->success();
        } catch (Exception $exception)
        {
            throw  $exception;
        }
    }

    public function rolemenu(Request $request)
    {
        try
        {
            $role = Role::find($request->id);
            $role->menus()->sync($request->menuids);
            return $this->success();

        } catch (Exception $exception)
        {
            throw  $exception;
        }
    }

    public function rolemenupath(Request $request)
    {
        try
        {
            $res=[];
            $role = Role::find($request->id);
            $menus = $role->menus->where('menutype','=','03');
            foreach ($menus as $menu){
                $root = Menu::find($menu->pid);
                array_push($res,[$root->pid,$menu->pid,$menu->id]);
            }
            return [
                'code'=>1,
                'msg'=>'ok',
                'result'=>$res
            ];
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function getusers(Request $request)
    {
        try
        {
            $role = Role::find($request->id);
            return $role->users()->get();
        } catch (Exception $exception)
        {

        }
    }

    public function getmenus(Request $request)
    {
        try
        {
            $role = Role::find($request->id);
            return $role->menus()->get();
        } catch (Exception $exception)
        {

        }
    }

    public function getrolerel(Request $request)
    {
        try
        {
            $role = Role::find($request->id);
            return [
                'code'=>1,
                'msg'=>'ok',
                'result'=>$role
            ];

        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

}
