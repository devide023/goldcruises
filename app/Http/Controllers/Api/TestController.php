<?php

namespace App\Http\Controllers\Api;

use App\Code\DataPermission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TestController extends Controller
{
    use DataPermission;

    //
    public function test(Request $request)
    {
        try
        {
           return cal_info(0);

        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function orgids(Request $request)
    {
        try
        {
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $this->current_user_datapermission()
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
