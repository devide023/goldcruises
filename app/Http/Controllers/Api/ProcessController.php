<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Process;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;

class ProcessController extends Controller
{
    //
    public function list(Request $request)
    {
        try {
            $query = Process::query();
            $pagesize = $request->pagesize ?? 15;

            return ['code'   => 1,
                    'msg'    => 'ok',
                    'result' => $query->paginate($pagesize)
            ];
        }
        catch (Exception $exception) {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }
}
