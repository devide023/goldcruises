<?php

namespace App\Http\Controllers\Api\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HotelReportController extends Controller
{
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
            $sql = 'SELECT t1.name,t2.* ,
(SELECT NAME FROM sys_organize WHERE id = t2.orgid) AS orgname
FROM sys_roomtype AS t1
JOIN
(
SELECT ta.orgid,tb.roomtypeid,SUM(qty) AS qty,SUM(price*qty) AS je FROM sys_hotelbook ta JOIN sys_hotelbookdetail tb
ON ta.id = tb.bookid and ta.status =2 and curdate()< ta.edate ';
            /*if (!is_null($request->date))
            {
                $sql = $sql . ' AND
(
	ta.bdate BETWEEN \'' . $date[0] . '\' AND \'' . $date[1] . '\'
	OR
	ta.edate BETWEEN \'' . $date[0] . '\' AND \'' . $date[1] . '\'
) ';
            }*/
            if(!is_null($roomtypeid)){
                $sql = $sql . ' and tb.roomtypeid='.$roomtypeid;
            }
            if (!is_null($orgid))
            {

                $sql = $sql . ' AND ta.orgid = ' . $orgid;
            }

            $sql = $sql . ' GROUP BY ta.orgid,tb.roomtypeid
) t2
ON t1.id = t2.roomtypeid
ORDER BY t2.orgid ASC';
            $result = DB::select( strtolower($sql));
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
}
