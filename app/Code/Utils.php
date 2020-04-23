<?php


namespace App\Code;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait Utils
{
    public function get_current_user_route ()
    {
        try
        {
            $sub = DB::table('roleuser')->where('userid', '=', Auth::id());

            $query = DB::table('roleroute')->joinSub($sub, 'tb', 'roleroute.roleid', '=', 'tb.roleid')
                ->join('route', 'roleroute.routeid', '=', 'route.id')->select([
                    'route.id',
                    'route.route',
                    'route.status'
                ])->distinct();
            return $query->get();
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }
}
