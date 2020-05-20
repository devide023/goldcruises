<?php

namespace App\Code;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait DataPermission
{
    public function current_user_datapermission()
    {
        try
        {
            $orgids = DB::table('userpermission')->where('userid', Auth::id())->pluck('orgid');
            if (!is_null($orgids))
            {
                return $orgids;
            } else
            {
                $orgids = DB::table('userorg')->where('userid',Auth::id())->pluck('departmentid');
                return $orgids;
            }
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }

    }
}
