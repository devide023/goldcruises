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
            $a = [
                [
                    'userid'   => 1,
                    'username' => 'a',
                    'cnt'      => 3
                ],
                [
                    'userid'   => 2,
                    'username' => 'b',
                    'cnt'      => 4
                ],
                [
                    'userid'   => 3,
                    'username' => 'c',
                    'cnt'      => 5
                ]
            ];
            return collect($a)->map(function ($item)
            {
                return collect($item)->only(['userid','cnt']);
            });


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
