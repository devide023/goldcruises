<?php


namespace App\Code;


use Illuminate\Support\Facades\DB;

trait UserTrail
{
    public function usermenubyid($userid){
        $menu = DB::table('roleuser')->where('roleuser.userid', $userid)
            ->join('rolemenu', 'roleuser.roleid', '=', 'rolemenu.roleid')
            ->join('menu', 'rolemenu.menuid', '=', 'menu.id')->select([
                'menu.id',
                'menu.pid',
                'menu.menucode as code',
                'menu.name as title',
                'menu.menutype',
                'menu.path',
                'menu.viewpath',
                'menu.icon',
            ])->where('menu.status', '=', 1)->distinct()->orderBy('seq','asc')->get();
        return $menu;
    }

}
