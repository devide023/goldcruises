<?php

namespace App\Http\Controllers\Api\Repair;

use App\Http\Controllers\Controller;
use App\Models\Repair;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepairController extends Controller
{
    //
    public function list(Request $request)
    {
        try
        {
            $pagesize = $request->pagesize ?? 15;
            $query = Repair::query();
            $query->when(!is_null($request->repairno), function (Builder $q) use ($request)
            {
                return $q->where('repairno', $request->repairno);
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
            $user = Auth::user();
            $repair = Repair::create([
                'status'     => $request->status,
                'repairno'   => $request->repairno,
                'title'      => $request->title,
                'content'    => $request->substance,
                'adduserid'  => $user->id,
                'adduser'    => $user->name,
                'addusertel' => $request->addusertel,
                'addtime'    => now(),
                'note'       => $request->note
            ]);
            if ($repair->id > 0)
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
            $repair = Repair::find($request->id);
            $ok = $repair->update([

            ]);
            if ($ok)
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

    public function find(Request $request)
    {
        try
        {
            $repair = Repair::find($request->id);
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $repair
            ];

        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }
}
