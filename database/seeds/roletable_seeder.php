<?php

use Illuminate\Database\Seeder;

class roletable_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Models\Role::create([
            'status' => 1,
            'name' => '系统管理员',
            'note'=>'',
            'adduserid' => \Illuminate\Support\Arr::random([10,11,12,13]),
            'addtime' => now()
        ]);
        \App\Models\Role::create([
            'status' => 1,
            'name' => '董事长',
            'note'=>'',
            'adduserid' => \Illuminate\Support\Arr::random([10,11,12,13]),
            'addtime' => now()
        ]);
        \App\Models\Role::create([
            'status' => 1,
            'name' => '总经理',
            'note'=>'',
            'adduserid' => \Illuminate\Support\Arr::random([10,11,12,13]),
            'addtime' => now()
        ]);
        \App\Models\Role::create([
            'status' => 1,
            'name' => '副总经理',
            'note'=>'',
            'adduserid' => \Illuminate\Support\Arr::random([10,11,12,13]),
            'addtime' => now()
        ]);
        \App\Models\Role::create([
            'status' => 1,
            'name' => '部长',
            'note'=>'',
            'adduserid' => \Illuminate\Support\Arr::random([10,11,12,13]),
            'addtime' => now()
        ]);
        \App\Models\Role::create([
            'status' => 1,
            'name' => '副部长',
            'note'=>'',
            'adduserid' => \Illuminate\Support\Arr::random([10,11,12,13]),
            'addtime' => now()
        ]);
        \App\Models\Role::create([
            'status' => 1,
            'name' => '业务员',
            'note'=>'',
            'adduserid' => \Illuminate\Support\Arr::random([10,11,12,13]),
            'addtime' => now()
        ]);
    }
}
