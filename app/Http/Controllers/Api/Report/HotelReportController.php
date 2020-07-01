<?php

namespace App\Http\Controllers\Api\Report;

use App\Code\MyArrayTool;
use App\Code\RemainRoomTypeQty;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HotelReportController extends Controller
{
    use MyArrayTool;
    use RemainRoomTypeQty;

    //
    /*
     * 房间预订报表
     */
    public function report_room_book(Request $request)
    {
        try
        {
            $date = $request->date;
            $orgid = $request->agent;
            $roomtypeid = $request->roomtype;
            $sql = 'select 
                  t1.*,
                  (select 
                    ifnull(sum(qty), 0) 
                  from
                    sys_agentplace a,
                    sys_agentplacedetail b 
                  where a.id = b.agentplaceid 
                    and a.agentid = t1.orgid 
                    and a.shipno = t1.shipno 
                    and b.roomtypeid = t1.roomtypeid) as totalqty,
                  t2.name as agentname,
                  t3.name as shipname,
                  t4.name as roomtypename 
                from
                  (select 
                    ta.orgid,
                    ta.shipno,
                    tb.roomtypeid,
                    sum(tb.qty) as qty,
                    sum(tb.amount) as amount 
                  from
                    sys_hotelbook ta,
                    sys_hotelbookdetail tb 
                  where ta.id = tb.bookid 
                    and curdate() < ta.edate 
                    and ta.status in (1, 2)
                    ';
            if (!is_null($request->shipno))
            {
                $sql = $sql . ' and ta.shpno =\'' . $request->shipno . '\'';
            }
            if (!is_null($request->agentid))
            {
                $sql = $sql . ' and ta.orgid =' . $request->agentid;
            }
            if (!is_null($request->roomtypeid))
            {
                $sql = $sql . ' and tb.roomtypeid =' . $request->roomtypeid;
            }
            $sql = $sql . ' group by ta.orgid,
                        ta.shipno,
                        tb.roomtypeid) t1,
                      sys_organize t2,
                      sys_ship t3,
                      sys_roomtype t4 
                    where t1.orgid = t2.id 
                      and t1.shipno = t3.code 
                      and t1.roomtypeid = t4.id ';
            $result = DB::select(strtolower($sql));
            $orgialdata = collect($result);
            $agentlist = $this->array_columns($result, 'orgid,shipno,agentname,shipname');
            $datas = collect($agentlist)->unique();
            $res = [];
            foreach ($datas as $data)
            {
                $roomtypes = [];
                $agentid = $data['orgid'];
                $shipno = $data['shipno'];
                $rooms = $orgialdata->where('orgid', $agentid)->where('shipno', $shipno);
                foreach ($rooms as $room)
                {
                    array_push($roomtypes, [
                        'roomtypeid'   => $room->roomtypeid,
                        'roomtypename' => $room->roomtypename,
                        'qty'          => $room->qty,
                        'totalqty'     => $room->totalqty,
                        'amount'       => $room->amount
                    ]);
                }
                array_push($res, [
                    'agentid'   => $agentid,
                    'agentname' => $data['agentname'],
                    'shipno'    => $shipno,
                    'shipname'  => $data['shipname'],
                    'roomtypes' => $roomtypes
                ]);
            }
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $res
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
     * 当日房间预订情况
     */
    public function current_bookroom_qty(Request $request)
    {
        try
        {
            $rq = $request->rq ?? date('Y-m-d');
            $agentid = $request->agentid;
            $result = $this->curdate_bookroomtype_qty($rq, $agentid);
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $result
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
     * 房间日期报表
     */
    public function date_roombook_report(Request $request)
    {
        try
        {
            if (is_null($request->date))
            {
                return [
                    'code' => 0,
                    'msg'  => '请选择查询时段'
                ];
            }
            $ksrq = $request->date[0];
            $jsrq = $request->date[1];
            $sql = 'call hotel_book_report(\'' . $ksrq . '\',\'' . $jsrq . '\');';
            $result = collect(DB::select($sql));
            $group = $result->map(function ($item)
            {
                return collect($item)->only([
                    'rq',
                    'bookcnt'
                ]);
            })->unique();
            $group->each(function ($i) use ($result)
            {
                $filter = $result->where('rq', $i['rq'])->where('bookcnt', $i['bookcnt']);
                $details = $filter->map(function ($i)
                {
                    return collect($i)->only([
                        'roomtypeid',
                        'qty',
                        'amount'
                    ]);
                });
                $i['details'] = array_values($details->toArray());
            });
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => array_values($group->toArray())
            ];
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

    public function date_bookreport_detial(Request $request)
    {
        try
        {
            if (is_null($request->rq))
            {
                return $this->error();
            }
            $sql = '
            select 
  t1.*,
  t2.name as agentname,
  t3.name as roomtypename 
from
  (select 
    ta.orgid,
    tb.roomtypeid,
    sum(tb.qty) qty 
  from
    sys_hotelbook ta,
    sys_hotelbookdetail tb 
  where ta.id = tb.bookid 
    and ta.status in (1, 2) 
    and (
      ta.bdate = \'' . $request->rq . '\' 
      or (
        ta.bdate <= \'' . $request->rq . '\' 
        and ta.edate > \'' . $request->rq . '\'
      )
    ) 
  group by ta.orgid,
    tb.roomtypeid) t1,
  sys_organize t2,
  sys_roomtype t3 
where t1.orgid = t2.id 
  and t1.roomtypeid = t3.id 
  order by t1.orgid asc
            ';
            $result = DB::select($sql);
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $result
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
     * 代理商房型日期报表
     */
    public function agent_date_roombook(Request $request)
    {
        try
        {
            if (is_null($request->date))
            {
                return [
                    'code' => 0,
                    'msg'  => '请选择查询时段'
                ];
            }
            $ksrq = $request->date[0];
            $jsrq = $request->date[1];
            $sql = 'call agent_date_bookqty(\'' . $ksrq . '\',\'' . $jsrq . '\');';
            $result = collect(DB::select($sql));
            $group = $result->map(function ($item){
               return collect($item)->only(['rq','agentid','bookcnt','agentname']);
            })->unique();
            foreach ($group as $item){
                $rq = $item['rq'];
                $agentid = $item['agentid'];
                $bookcnt = $item['bookcnt'];
                $filters = $result->where('rq','=',$rq)
                    ->where('agentid','=',$agentid);
                $details = $filters->map(function ($i){
                   return collect($i)->only(['roomtypeid','qty']);
                });
                $item['details'] = array_values($details->toArray());
            }
            return [
              'code'=>1,
              'msg'=>'ok',
              'result'=>array_values($group->toArray())
            ];
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

}
