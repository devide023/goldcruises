<?php

namespace App\Http\Controllers\Api\ECharts;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EChartsController extends Controller
{
    //
    /**
     * @param Request $request
     * @return array
     * 日预订数量
     */
    public function date_book_count(Request $request)
    {
        try
        {

            $rq1 = date('Y-m-01');
            $rq2 = date('Y-m-d', strtotime($rq1 . '+1 month -1 day'));
            //$rq1 = '2020-07-01';
            //$rq2= '2020-08-31';
            $result = collect(DB::select('call hotel_book_report(\'' . $rq1 . '\',\'' . $rq2 . '\')'));

            $roomtypes = RoomType::where('status', '=', 1)->get([
                'id',
                'name'
            ]);
            $dates = [];
            $tempdate = $rq1;
            while (strtotime($tempdate) <= strtotime($rq2))
            {
                array_push($dates, $tempdate);
                $tempdate = date('Y-m-d', strtotime($tempdate . '+1 day'));
            }
            $rang = [];
            array_push($rang, $dates[0]);
            array_push($rang, $dates[count($dates) - 1]);
            $resdata = [];
            foreach ($dates as $date)
            {
                $row = [];
                $row['日期'] = $date;
                foreach ($roomtypes as $roomtype)
                {
                    $finditem = $result->where('rq', '=', $date)->where('roomtypeid', $roomtype->id);
                    if (count($finditem) > 0)
                    {
                        $row[$roomtype->name] = (float)$finditem->first()->qty;
                    } else
                    {
                        $row[$roomtype->name] = 0;
                    }
                }
                array_push($resdata, $row);
            }
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'rang'   => $rang,
                'result' => $resdata,
                'dates'  => $dates
            ];
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

    /**
     * @param Request $request
     * @return array
     * 代理商预定人数、房间数
     */
    public function agent_book_cnt(Request $request)
    {
        try
        {
            $sql = 'select t1.name as 代理商,t2.bookcnt as 人数,t3.qty as 房间数 from
 (
 select id,name from sys_organize where orgtype=\'05\'
 ) t1 left join
 (
 select orgid,sum(bookcount) as bookcnt from sys_hotelbook where status in(1,2) group by orgid
 ) t2
 on t1.id = t2.orgid
 left join
 (
 select t1.orgid,sum(t2.qty) as qty from sys_hotelbook t1,sys_hotelbookdetail t2 where t1.id = t2.bookid and
 t1.status in(1,2) group by t1.orgid
 ) t3
on t1.id = t3.orgid order by t2.bookcnt asc';
            $result = DB::select($sql);
            return [
                'code'       => 1,
                'msg'        => 'ok',
                'dimensions' => [
                    '代理商',
                    '人数',
                    '房间数'
                ],
                'result'     => $result
            ];

        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

    /**
     * @param Request $request
     * @return array
     * 代理商日期房型预定量
     */
    public function agent_date_book_room_cnt(Request $request)
    {
        try
        {
            $agentid = 0;
            $agent = DB::table('userorg')->where('userid', Auth::id())->select('departmentid')->first();
            $agentid = $agent->departmentid;
            $roomtypes = DB::table('roomtype')->get([
                'id',
                'shortname'
            ]);
            $rq1 = date('Y-m-01');
            $rq2 = date('Y-m-d', strtotime($rq1 . '+1 month -1 day'));
            $dates = [];
            $resdata = [];
            $tempdate = $rq1;
            while (strtotime($tempdate) <= strtotime($rq2))
            {
                array_push($dates, $tempdate);
                $bookcntsql = "select ifnull(sum(bookcount),0) as bookcnt from sys_hotelbook where status in(1,2) and orgid = $agentid
and bdate<='$tempdate' and '$tempdate'<edate";
                $bookcntresult = collect(DB::select($bookcntsql));
                $row = [];
                $row['日期'] = $tempdate;
                $row['人数'] = count($bookcntresult) != 0 ? $bookcntresult[0]->bookcnt : 0;
                $sql = 'select t1.*,t2.name from 
(select tb.roomtypeid,sum(tb.qty) as qty from sys_hotelbook ta,sys_hotelbookdetail tb
where ta.id = tb.bookid
and ta.status in (1,2)
and ta.orgid = ' . $agentid . ' and ta.bdate<= \'' . $tempdate . '\' and \'' . $tempdate . '\'< ta.edate
group by tb.roomtypeid) t1,
sys_roomtype t2
 where t1.roomtypeid = t2.id';
                $roombookresult = collect(DB::select($sql));
                foreach ($roomtypes as $roomtype)
                {
                    $finditem = $roombookresult->where('roomtypeid', $roomtype->id)->first();

                    if (!is_null($finditem))
                    {
                        $row[$roomtype->shortname] = $finditem->qty;
                    } else
                    {
                        $row[$roomtype->shortname] = 0;
                    }
                }
                array_push($resdata, $row);
                $tempdate = date('Y-m-d', strtotime($tempdate . '+1 day'));
            }
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $resdata
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
