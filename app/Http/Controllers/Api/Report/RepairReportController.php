<?php

namespace App\Http\Controllers\Api\Report;

use App\Code\TSql;
use App\Http\Controllers\Controller;
use App\Models\Sql;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RepairReportController extends Controller
{
    use TSql;
    //
    public function repairship(Request $request)
    {
        try
        {
            $sql = $this->get_tsql($request);
            $params = $sql->params()->get();
            $queryparam = [];
            foreach ($params as $param){
                $key = $param->paramname;
                $queryparam[$key] = $request[$key];
            }
            $result = DB::select(strtolower($sql->tsql),$queryparam);
            return [
                'code'=>1,
                'msg'=>'ok',
                'result'=>$result,
                'params'=>$queryparam
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
