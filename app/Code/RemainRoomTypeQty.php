<?php


namespace App\Code;


use Illuminate\Support\Facades\DB;

trait RemainRoomTypeQty
{
    /*
     * 房型剩余数量
     */
    public function roomtype_remain_qty($shipno, $agentid, $roomtypeids = [])
    {
        try
        {
            $ids = implode(',', $roomtypeids);
            $sql = 'select 
                  t1.roomtypeid,
                  t1.qty,
                  ifnull(t2.qty, 0) as qty1,
                  t1.qty - ifnull(t2.qty, 0) as rqty 
                from
                  (select 
                    ta.shipno,
                    ta.agentid,
                    tb.roomtypeid,
                    sum(tb.qty) as qty 
                  from
                    sys_agentplace ta,
                    sys_agentplacedetail tb 
                  where ta.id = tb.agentplaceid 
                    and ta.agentid = ' . $agentid . '
                    and ta.shipno = \'' . $shipno . '\' 
                    and tb.roomtypeid in (' . $ids . ') 
                  group by tb.roomtypeid,
                    ta.agentid,
                    ta.shipno) as t1 
                  left join 
                    (select 
                      ta.shipno,
                      ta.orgid,
                      tb.roomtypeid,
                      sum(qty) as qty 
                    from
                      sys_hotelbook ta,
                      sys_hotelbookdetail tb 
                    where ta.id = tb.bookid 
                      and curdate() < ta.edate 
                      and ta.status in (1, 2) 
                      and ta.shipno = \'' . $shipno . '\' 
                      and ta.orgid = ' . $agentid . ' 
                      and tb.roomtypeid in (' . $ids . ') 
                    group by tb.roomtypeid,
                      ta.orgid,
                      ta.shipno 
                    order by ta.orgid asc,
                      tb.roomtypeid asc) as t2 
                    on t1.shipno = t2.shipno 
                    and t1.agentid = t2.orgid 
                    and t1.roomtypeid = t2.roomtypeid ';
            $result = DB::select($sql);
            return $result;
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }
}
