<?php

namespace App\Http\Controllers\Api\Hotel;

use App\Http\Controllers\Controller;
use App\Models\AgentPlace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentPlaceController extends Controller
{
    /*
     * 添加代理商控位
     */
    public function add_agent_place(Request $request)
    {
        try
        {
            AgentPlace::create([
                'shipno' => $request->shipno,
                'agentid' => $request->agentid,
                'status' => $request->status,
                'addtime' => now(),
                'adduserid' => Auth::id()
            ]);
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }
}
