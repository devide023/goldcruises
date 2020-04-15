<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organize;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrganizeController extends Controller
{
    //
    public function list(Request $request)
    {
        try
        {
            $query = Organize::query();
            $query->when($request->id, function (Builder $q) use ($request)
            {
                return $q->where('id', $request->id);
            });
            $query->when(!is_null($request->pid), function (Builder $q) use ($request)
            {
                return $q->where('pid','=', $request->pid);
            });
            $query->when($request->name, function (Builder $q) use ($request)
            {
                return $q->where('name', 'like', '%' . $request->name . '%');
            });
            $query->when($request->orgcode, function (Builder $q) use ($request)
            {
                return $q->where('orgcode', $request->orgcode);
            });
            $query->when($request->status, function (Builder $q) use ($request)
            {
                return $q->where('status', $request->status);
            });
            $query->when($request->orgtype, function (Builder $q) use ($request)
            {
                return $q->where('orgtype', $request->orgtype);
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
                'result' => $query->paginate()
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
            $org = Organize::create([
                'status'    => $request->status,
                'orgtype'   => $request->orgtype,
                'name'      => $request->name,
                'leader'    => $request->leader,
                'pid'       => $request->pid,
                'orgcode'   => $request->orgcode,
                'logo'      => $request->logo,
                'addtime'   => now(),
                'adduserid' => Auth::user()->id
            ]);
            if ($org->id > 0)
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
            $org = Organize::find($request->id);
            if (!is_null($org))
            {
                $isok = $org->update([
                    'status'  => $request->status,
                    'orgtype' => $request->orgtype,
                    'name'    => $request->name,
                    'leader'  => $request->leader,
                    'pid'     => $request->pid,
                    'orgcode' => $request->orgcode,
                    'logo'    => $request->logo,
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
                    'msg'  => '参数错误'
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
            DB::transaction(function () use ($request)
            {
                $org = Organize::find($request->id);
                $org->users()->detach();
                $org->delete();
            });
            return $this->success();
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }
}
