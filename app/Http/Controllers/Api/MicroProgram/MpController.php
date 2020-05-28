<?php

namespace App\Http\Controllers\Api\MicroProgram;

use App\Code\AuditIds;
use App\Code\DataPermission;
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
    use AuditIds;
    use DataPermission;

    public function userinfo(Request $request)
    {
        try
        {
            $user = User::with(['mpfuns','mpmenus','orgnodes'])->find(Auth::id());
            return [
                'code'=>1,
                'msg'=>'ok',
                'result'=>$user
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


            $taskquery = Repair::query();
            $taskquery = $taskquery->whereIn('id', $this->current_audit_ids(1));
            $taskcnt = $taskquery->whereIn('orgid', $this->current_user_datapermission())->count();

            $menus = DB::table('mpusermenu')->join('mpmenu', 'mpusermenu.mpmenuid', '=', 'mpmenu.id')
                ->where('mpusermenu.userid', Auth::id())->select([
                    'mpmenu.*'
                ])->orderBy('seq', 'asc')->get();
            foreach ($menus as $menu)
            {
                $has = strstr($menu->pagepath, 'tasklist');
                if ($has)
                {

                    $menu->taskcnt = $taskcnt;
                } else
                {
                    $menu->taskcnt = 0;
                }
            }
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
                'code'   => 1,
                'msg'    => 'ok',
                'result' => MpMenu::all()
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
            $userid = $request->id ?? 0;
            $user = User::find($userid);
            $menudata = [];
            $fundata = [];
            foreach ($request->mpmenus as $mpmenu)
            {
                array_push($menudata, ['mpmenuid' => $mpmenu]);
            }
            foreach ($request->mpfuns as $mpfun)
            {
                array_push($fundata, ['funid' => $mpfun]);
            }
            DB::beginTransaction();
            $user->mpmenus()->delete();
            $r1 = $user->mpmenus()->createMany($menudata);
            $user->mpfuns()->delete();
            $r2 = $user->mpfuns()->createMany($fundata);
            if (count($r1) == count($menudata) && count($r2) == count($fundata))
            {
                DB::commit();
                return $this->success();
            } else
            {
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
            $userid = $request->userid ?? 0;
            $user = User::find($userid);
            $funs = $user->mpfuns()->get();
            $menus = $user->mpmenus()->get();
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => [
                    'funs'  => $funs,
                    'menus' => $menus
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
