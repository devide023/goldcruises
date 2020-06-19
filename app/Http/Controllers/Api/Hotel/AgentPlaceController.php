<?php

namespace App\Http\Controllers\Api\Hotel;

use App\Http\Controllers\Controller;
use App\Models\AgentPlace;
use App\Models\Organize;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgentPlaceController extends Controller
{
    /*
     * 代理商控位列表
     */
    public function agent_place_list(Request $request)
    {
        try
        {
            $pagesize = $request->pagesize ?? 15;
            $query = AgentPlace::query();
            $query->when(!is_null($request->agentid), function (Builder $q) use ($request)
            {
                $q->where('agentid', $request->agentid);
            });
            $query->when(!is_null($request->shipno), function (Builder $q) use ($request)
            {
                $q->where('shipno', $request->shipno);
            });
            $query->when(!is_null($request->roomtypeid), function (Builder $q) use ($request)
            {
                $q->whereHas('details', function (Builder $s) use ($request)
                {
                    $s->where('roomtypeid', $request->roomtypeid);
                });
            });

            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $query->orderBy('id', 'desc')->paginate($pagesize)
            ];
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

    /*
     * 添加代理商控位
     */
    public function add_agent_place(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $hav = AgentPlace::where('shipno', $request->shipno)->where('agentid', $request->agentid)->count();
            if ($hav > 0)
            {
                return [
                    'code' => 0,
                    'msg'  => '该代理商已经对该邮轮房型设置过！'
                ];
            }
            $agentname = Organize::find($request->agentid)->name;
            $agent = AgentPlace::create([
                'shipno'    => $request->shipno,
                'agentid'   => $request->agentid,
                'agentname' => $agentname,
                'status'    => $request->status,
                'addtime'   => now(),
                'adduserid' => Auth::id()
            ]);
            $postdata = [];
            foreach ($request->roomtypeqty as $item)
            {
                array_push($postdata, [
                    'roomtypeid' => $item['roomtypeid'],
                    'qty'        => $item['qty']
                ]);
            }
            $agent->details()->createMany($postdata);
            DB::commit();
            if ($agent->id > 0)
            {
                return $this->success();
            } else
            {
                return $this->error();
            }
        } catch (Exception $exception)
        {
            DB::rollBack();
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

    public function edit_agent_place(Request $request)
    {
        try
        {
            $id = $request->id ?? 0;
            $agentplace = AgentPlace::find($id);
            DB::beginTransaction();
            $postdata = [];
            foreach ($request->roomtypeqty as $item)
            {
                array_push($postdata, [
                    'roomtypeid' => $item['roomtypeid'],
                    'qty'        => $item['qty']
                ]);
            }
            $agentplace->details()->delete();
            $agentplace->details()->createMany($postdata);
            DB::commit();
            return $this->success();
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }
}
