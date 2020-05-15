<?php

use App\Models\MpMenu;
use Illuminate\Database\Seeder;

class mpmenu_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        MpMenu::create([
            'name' => '我要报修',
            'pagepath' => '/pages/repair/repairadd',
            'icon' => 'star-filled',
            'adduserid' =>Auth::id(),
            'addtime' => now(),
            'size' => 30,
            'fontsize' => '30rpx',
            'color' => '#005500'
        ]);
        MpMenu::create([
            'name' => '我的报修',
            'pagepath' => '/pages/my/index',
            'icon' => 'bars',
            'adduserid' =>Auth::id(),
            'addtime' => now(),
            'size' => 30,
            'fontsize' => '30rpx',
            'color' => '#005500'
        ]);
        MpMenu::create([
            'name' => '我的任务',
            'pagepath' => '/pages/my/mybill',
            'icon' => 'list',
            'adduserid' =>Auth::id(),
            'addtime' => now(),
            'size' => 30,
            'fontsize' => '30rpx',
            'color' => '#005500'
        ]);
        MpMenu::create([
            'name' => '报修列表',
            'pagepath' => '/pages/repair/list',
            'icon' => 'gear',
            'adduserid' =>Auth::id(),
            'addtime' => now(),
            'size' => 30,
            'fontsize' => '30rpx',
            'color' => '#005500'
        ]);
        MpMenu::create([
            'name' => '报表',
            'pagepath' => '/pages/report/index',
            'icon' => 'flag',
            'adduserid' =>Auth::id(),
            'addtime' => now(),
            'size' => 30,
            'fontsize' => '30rpx',
            'color' => '#005500'
        ]);
    }
}
