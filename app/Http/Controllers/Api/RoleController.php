<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //

    public function list(Request $request)
    {
        try
        {
            $query = Role::query();
            $query->when($request->name, function (Builder $q) use ($request)
            {
                return $q->where('name', 'like', '%' . $request->name . '%');
            });
            $query->when(!is_null($request->ksrq) && !is_null($request->jsrq), function (Builder $q) use ($request)
            {
                return $q->whereBetween('addtime', [
                    $request->ksrq .' 00:00:00',
                    $request->jsrq .' 23:59:59'
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
            $role = Role::create([
                'status' => $request->status,
                'name' => $request->name,
                'adduserid' => $request->adduser,
                'note' => $request->note,
                'addtime' => now()
            ]);
            if ($role->id > 0)
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
                $isok = $role->update([
                    'status' => $request->status,
                    'note'   => $request->note,
                    'name'   => $request->name,
                ]);
                if ($isok)
                {
                    return $this->success();
                } else
                {
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
            $ret = Role::destroy($request->id);
            if($ret>0){
               return $this->success();
            }
            else
            {
                return $this->error();
            }
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

}
