<?php

namespace App\Http\Controllers\Api\Hotel;

use App\Http\Controllers\Controller;
use App\Models\AgentPlace;
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
            $pagesize=$request->pagesize??15;
            $query = AgentPlace::query();


            return [
              'code'=>1,
              'msg'=>'ok',
              'result'=>$query->orderBy('id','desc')->paginate($pagesize)
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
            $agent = AgentPlace::create([
                'shipno' => $request->shipno,
                'agentid' => $request->agentid,
                'status' => $request->status,
                'addtime' => now(),
                'adduserid' => Auth::id()
            ]);
            $postdata=[];
            foreach ($request->roomtypeqty as $item){
                array_push($postdata,[
                   'roomtypeid'=>$item['roomtypeid'],
                   'qty'=>$item['qty']
                ]);
            }
            $agent->details()->createMany($postdata);
            DB::commit();
            if($agent->id>0){
                return $this->success();
            }
            else{
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
}
