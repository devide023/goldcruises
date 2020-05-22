<?php

namespace App\Http\Controllers\Api\MicroProgram;

use App\Http\Controllers\Controller;
use App\Models\MpMenu;
use App\Models\Repair;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            $mpfuns = $user->mpfuns()->get();
            $mpmenus = $user->mpmenus()->get();
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
                    'orgnode'  => $nodes,
                    'funs'     => $mpfuns,
                    'menus'    => $mpmenus
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
            $menus = DB::table('mpusermenu')->join('mpmenu','mpusermenu.mpmenuid','=','mpmenu.id')
                ->where('mpusermenu.userid',Auth::id())
                ->select([
                    'mpmenu.*'
                ])->orderBy('seq','asc')->get();
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $menus
            ];

        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }

    }

    public function menuslist(Request $request)
    {
        try
        {
            return [
                'code'=>1,
                'msg'=>'ok',
                'result'=>MpMenu::all()
            ];
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

    public function usermpsetting(Request $request)
    {
        try
        {
            $userid = $request->id??0;
            $user = User::find($userid);
            $menudata=[];
            $fundata=[];
            foreach ($request->mpmenus as $mpmenu){
                array_push($menudata,['mpmenuid'=>$mpmenu]);
            }
            foreach ($request->mpfuns as $mpfun){
                array_push($fundata,['funid'=>$mpfun]);
            }
            DB::beginTransaction();
            $user->mpmenus()->delete();
            $r1 = $user->mpmenus()->createMany($menudata);
            $user->mpfuns()->delete();
            $r2 = $user->mpfuns()->createMany($fundata);
            if(count($r1)==count($menudata) && count($r2)==count($fundata)){
                DB::commit();
                return $this->success();
            }
            else{
                DB::rollBack();
                return $this->error();
            }

        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

    public function getusermpsetting(Request $request)
    {
        try
        {
            $userid = $request->userid??0;
            $user = User::find($userid);
            $funs = $user->mpfuns()->get();
            $menus = $user->mpmenus()->get();
            return [
                'code'=>1,
                'msg'=>'ok',
                'result'=>[
                    'funs'=>$funs,
                    'menus'=>$menus
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

}
