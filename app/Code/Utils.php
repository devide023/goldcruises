<?php


namespace App\Code;


use App\Models\Menu;
use App\Models\Organize;
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
    private  function get_sub_node($id){
        $nodes=[];
        $subnodes = Organize::where('status','=',1)
            ->where('pid',$id)->get();
        foreach ($subnodes as $subnode){
            $has = Organize::where('pid',$subnode->id)->where('status','=',1)->count();
            $pushdata = [
                'id'=>$subnode->id,
                'parentid'=>$subnode->pid,
                'label'=>$subnode->name,
                'orgtype'=>$subnode->orgtype,
                'orgcode'=>$subnode->orgcode,
                'isedit'=>false,
            ];
            if($has>0){
                $pushdata['children'] = $this->get_sub_node($subnode->id);
            }else
            {
                $pushdata['leaf']=true;
            }
            array_push($nodes,$pushdata);
        }

        return $nodes;
    }
    public function get_org_all_tree($id)
    {
        try
        {
            $all_nodes=[];
            $rootnodes = Organize::where('pid','=',$id)
            ->where('status','=',1)->get();
            foreach ($rootnodes as $rootnode){
                $has = Organize::where('pid',$rootnode->id)->where('status','=',1)->count();
                $pushdata = [
                    'id'=>$rootnode->id,
                    'parentid'=>$rootnode->pid,
                    'label'=>$rootnode->name,
                    'orgtype'=>$rootnode->orgtype,
                    'orgcode'=>$rootnode->orgcode,
                    'isedit'=>false,
                ];
                if($has>0){
                    $pushdata['children'] = $this->get_sub_node($rootnode->id);
                }else
                {
                    $pushdata['leaf']=true;
                }
                array_push($all_nodes,$pushdata);
            }
            return $all_nodes;
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function get_menu_all_tree($id=0)
    {
        try
        {
            $all_menus=[];
            $rootmenus = Menu::where('pid','=',$id)
                ->where('status','=',1)
                ->get();

            foreach ($rootmenus as $rootmenu)
            {
                $haschild = Menu::where('pid',$rootmenu->id)
                    ->where('status','=',1)
                    ->count();
                $data = [
                    'id'=>$rootmenu->id,
                    'parentid'=>$rootmenu->pid,
                    'label'=>$rootmenu->name,
                    'menucode'=>$rootmenu->menucode,
                    'menutype'=>$rootmenu->menutype,
                    'value'=>$rootmenu->id
                ];
                if($haschild>0){
                    $data['children']=$this->get_menu_sub_tree($rootmenu->id);;
                }else
                {
                    $pushdata['leaf']=true;
                }
                array_push($all_menus,$data);
            }
            return $all_menus;
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    private function get_menu_sub_tree($id){
        $menus=[];
        $submenus = Menu::where('pid','=',$id)
            ->where('status','=',1)
            ->get();
        foreach ($submenus as $submenu)
        {
            $haschild = Menu::where('pid',$submenu->id)
                ->where('status','=',1)
                ->count();
            $data = [
                'id'=>$submenu->id,
                'parentid'=>$submenu->pid,
                'label'=>$submenu->name,
                'menucode'=>$submenu->menucode,
                'menutype'=>$submenu->menutype,
                'value'=>$submenu->id
            ];
            if($haschild>0){
                $data['children']=$this->get_menu_sub_tree($submenu->id);
            }else
            {
                $pushdata['leaf']=true;
            }
            array_push($menus,$data);
        }
        return $menus;
    }

}
