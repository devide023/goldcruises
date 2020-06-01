<?php

namespace App\Code;

use App\Models\Organize;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait DataPermission
{
    private function get_suborg(&$ids,$orgid){
        $orgs = Organize::where('pid',$orgid);
        if($orgs->count()>0){
            foreach ($orgs->pluck('id') as $id){
                array_push($ids,$id);
                $this->get_suborg($ids,$id);
            }
        }
    }
    public function current_user_datapermission()
    {
        try
        {
            $cnt = DB::table('userpermission')->where('userid', Auth::id())->count();
            if ($cnt>0)
            {
                $orgids = DB::table('userpermission')->where('userid', Auth::id())->pluck('orgid');
                return $orgids;
            } else
            {
                $ids=[];
                $orgids = DB::table('userorg')->where('userid',Auth::id())->pluck('departmentid');
                foreach ($orgids as $orgid){
                    array_push($ids,$orgid);
                    $this->get_suborg($ids,$orgid);
                }
                return $ids;
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
