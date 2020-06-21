<?php

namespace App\Http\Controllers\Api\Report;

use App\Code\MyArrayTool;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HotelReportController extends Controller
{
    use MyArrayTool;

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


}
