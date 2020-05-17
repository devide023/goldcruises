<?php

use App\Models\Menu;
use App\Models\MenuType;
use Illuminate\Database\Seeder;

class menutable_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MenuType::create([
            'code'=>'01',
            'name'=>'菜单',
            'addtime'=>now(),
            'status'=>1
        ]);
        MenuType::create([
            'code'=>'02',
            'name'=>'页面',
            'addtime'=>now(),
            'status'=>1
        ]);
        MenuType::create([
            'code'=>'03',
            'name'=>'功能',
            'addtime'=>now(),
            'status'=>1
        ]);
        //
        $sys = Menu::create([
            'pid'       => 0,
            'name'      => '系统管理',
            'menucode'  => '01',
            'menutype'  => '01',
            'icon'      => 'setting',
            'adduserid' => \Illuminate\Support\Arr::random([11,12,13,14,15]),
            'addtime'   => now(),
            'status'    => 1
        ]);
        $usermgr = Menu::create([
            'pid'       => $sys->id,
            'name'      => '用户管理',
            'menucode'  => '01'.str_pad($sys->id,2,'0',STR_PAD_LEFT),
            'menutype'  => '02',
            'icon'      => 'user',
            'adduserid' => \Illuminate\Support\Arr::random([11,12,13,14,15]),
            'addtime'   => now(),
            'status'    => 1
        ]);
        $rolemgr = Menu::create([
            'pid'       => $sys->id,
            'name'      => '角色管理',
            'menucode'  => '01'.str_pad($sys->id+1,2,'0',STR_PAD_LEFT),
            'menutype'  => '02',
            'icon'      => 'role',
            'adduserid' => \Illuminate\Support\Arr::random([11,12,13,14,15]),
            'addtime'   => now(),
            'status'    => 1
        ]);
        $menumgr = Menu::create([
            'pid'       => $sys->id,
            'name'      => '菜单管理',
            'menucode'  => '01'.str_pad($sys->id+2,2,'0',STR_PAD_LEFT),
            'menutype'  => '02',
            'icon'      => 'menu',
            'adduserid' => \Illuminate\Support\Arr::random([11,12,13,14,15]),
            'addtime'   => now(),
            'status'    => 1
        ]);
        $basemgr = Menu::create([
            'pid'       => $sys->id,
            'name'      => '基础资料',
            'menucode'  => '01'.str_pad($sys->id+3,2,'0',STR_PAD_LEFT),
            'menutype'  => '02',
            'icon'      => 'base',
            'adduserid' => \Illuminate\Support\Arr::random([11,12,13,14,15]),
            'addtime'   => now(),
            'status'    => 1
        ]);
        $userf1 = Menu::create([
        'pid'       => $usermgr->id,
        'name'      => 'add',
        'menucode'  => $usermgr->menucode.str_pad(1,2,'0',STR_PAD_LEFT),
        'menutype'  => '03',
        'icon'      => 'user',
        'adduserid' => \Illuminate\Support\Arr::random([11,12,13,14,15]),
        'addtime'   => now(),
        'status'    => 1
    ]);
        $userf2 = Menu::create([
            'pid'       => $usermgr->id,
            'name'      => 'query',
            'menucode'  => $usermgr->menucode.str_pad(2,2,'0',STR_PAD_LEFT),
            'menutype'  => '03',
            'icon'      => 'user',
            'adduserid' => \Illuminate\Support\Arr::random([11,12,13,14,15]),
            'addtime'   => now(),
            'status'    => 1
        ]);
        $userf3 = Menu::create([
            'pid'       => $usermgr->id,
            'name'      => 'del',
            'menucode'  => $usermgr->menucode.str_pad(3,2,'0',STR_PAD_LEFT),
            'menutype'  => '03',
            'icon'      => 'user',
            'adduserid' => \Illuminate\Support\Arr::random([11,12,13,14,15]),
            'addtime'   => now(),
            'status'    => 1
        ]);
        $userf4 = Menu::create([
        'pid'       => $usermgr->id,
        'name'      => 'enable',
        'menucode'  => $usermgr->menucode.str_pad(4,2,'0',STR_PAD_LEFT),
        'menutype'  => '03',
        'icon'      => 'user',
        'adduserid' => \Illuminate\Support\Arr::random([11,12,13,14,15]),
        'addtime'   => now(),
        'status'    => 1
    ]);
        $userf5= Menu::create([
            'pid'       => $usermgr->id,
            'name'      => 'edit',
            'menucode'  => $usermgr->menucode.str_pad(5,2,'0',STR_PAD_LEFT),
            'menutype'  => '03',
            'icon'      => 'user',
            'adduserid' => \Illuminate\Support\Arr::random([11,12,13,14,15]),
            'addtime'   => now(),
            'status'    => 1
        ]);
    }
}
