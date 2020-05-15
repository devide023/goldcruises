<?php

namespace App\Http\Controllers\Api\MicroProgram;

use App\Http\Controllers\Controller;
use App\Models\MpMenu;
use App\Models\Repair;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MpController extends Controller
{
    //
    public function userinfo(Request $request)
    {
        try
        {
            $user = User::find(Auth::id());
            $nodes = $user->orgnodes()->where('main', '=', 1)->first([
                'organize.id',
                'organize.pid',
                'organize.name'
            ]);
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => [
                    'id'       => $user->id,
                    'usercode' => $user->usercode,
                    'name'     => $user->name,
                    'idno'     => $user->idno,
                    'tel'      => $user->tel,
                    'email'    => $user->email,
                    'orgnode'  => $nodes
                ]
            ];
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }

    }

    public function getmpmenu(Request $request)
    {
        try
        {
           $menus = MpMenu::all();
           return [
               'code'=>1,
               'msg'=>'ok',
               'result'=>$menus
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
