<?php

namespace App\Http\Controllers\Api\Hotel;

use App\Http\Controllers\Controller;
use App\Models\AgentPlace;
use App\Models\AgentPlaceDate;
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

    /*
     * 日期控位
     */
    public function add_agentplace_date(Request $request)
    {
        try
        {
            $postdata = [];
            $date = $request->bdate;
            if (!is_null($date))
            {
                if ($date[0] < date('Y-m-d'))
                {
                    return [
                        'code' => 0,
                        'msg'  => '开始日期应大于等于当前日期'
                    ];
                }
            }
            foreach ($request->details as $item)
            {
                if ($item['qty'] > 0)
                {
                    array_push($postdata, [
                        'roomtypeid' => $item['roomtypeid'],
                        'qty'        => $item['qty']
                    ]);
                }
            }
            if (count($postdata) == 0)
            {
                return [
                    'code' => 0,
                    'msg'  => '请设置该时段房型位置数'
                ];
            }
            $has = AgentPlaceDate::where('agentid', $request->agentid)->where('status', '=', 1)
                ->where('edate', '>=', date('Y-m-d'));
            if ($has->count() > 0)
            {
                return [
                    'code' => 0,
                    'msg'  => '该代理商已设置了日期位置'
                ];
            }
            DB::beginTransaction();
            $agentplacedate = AgentPlaceDate::create([
                'status'    => $request->status ?? 1,
                'bdate'     => !is_null($request->bdate) ? $request->bdate[0] : null,
                'edate'     => !is_null($request->bdate) ? $request->bdate[1] : null,
                'shipno'    => $request->shipno ?? '05',
                'agentid'   => $request->agentid,
                'addtime'   => now(),
                'adduserid' => Auth::id()
            ]);
            $agentplacedate->details()->createMany($postdata);
            DB::commit();
            if ($agentplacedate->id > 0)
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

    /*
     * 编辑日期控位
     */
    public function edit_agentplace_date(Request $request)
    {
        try
        {
            $postdata = [];
            $date = $request->bdate;
            if (!is_null($date))
            {
                if ($date[0] < date('Y-m-d'))
                {
                    return [
                        'code' => 0,
                        'msg'  => '开始日期应大于等于当前日期'
                    ];
                }
            }
            foreach ($request->details as $item)
            {
                if ($item['qty'] > 0)
                {
                    array_push($postdata, [
                        'roomtypeid' => $item['roomtypeid'],
                        'qty'        => $item['qty']
                    ]);
                }
            }
            if (count($postdata) == 0)
            {
                return [
                    'code' => 0,
                    'msg'  => '请设置该时段房型位置数'
                ];
            }
            DB::beginTransaction();
            $agentplacedate = AgentPlaceDate::where('id', $request->id);
            $ok = $agentplacedate->update([
                'bdate'   => !is_null($request->bdate) ? $request->bdate[0] : null,
                'edate'   => !is_null($request->bdate) ? $request->bdate[1] : null,
                'shipno'  => $request->shipno ?? '05',
                'agentid' => $request->agentid
            ]);
            $agentplacedate->details()->delete();
            $agentplacedate->details()->createMany($postdata);
            DB::commit();
            if ($ok)
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

    /*
     * 日期控位列表
     */
    public function agentplace_date_list(Request $request)
    {
        try
        {
            $pagesize = $request->pagesize ?? 15;
            $query = AgentPlaceDate::query();

            $query->when(!is_null($request->bdate), function (Builder $q) use ($request)
            {
                $q->whereBetween('bdate', $request->bdate);
            });
            $query->when(!is_null($request->edate), function (Builder $q) use ($request)
            {
                $q->whereBetween('edate', $request->edate);
            });
            $query->when(!is_null($request->agentid), function (Builder $q) use ($request)
            {
                $q->where('agentid', $request->agentid);
            });

            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $query->orderBy('bdate', 'asc')->paginate($pagesize)
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
     * 禁用启用日期控位
    */
    public function agentplace_date_status(Request $request)
    {
        try
        {
            $id = $request->id ?? 0;
            $tempstatus = 1;
            switch ($request->status)
            {
                case 1:
                    $tempstatus = 0;
                    break;
                case 0:
                    $tempstatus = 1;
                    break;
            }
            $ok = AgentPlaceDate::where('id', $id)->where('status', $tempstatus)->update([
                'status' => $request->status
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
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }
}
