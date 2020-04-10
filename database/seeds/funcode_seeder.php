<?php

use App\Models\FunCode;
use Illuminate\Database\Seeder;

class funcode_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        FunCode::create([
            'status' => 1,
            'code' => 'query',
            'name' => '查询',
            'addtime' => now(),
            'adduserid' => 1
        ]);
        FunCode::create([
           'status' => 1,
           'code' => 'add',
           'name' => '新增',
           'addtime' => now(),
           'adduserid' => 1
        ]);
        FunCode::create([
            'status' => 1,
            'code' => 'del',
            'name' => '删除',
            'addtime' => now(),
            'adduserid' => 1
        ]);
        FunCode::create([
            'status' => 1,
            'code' => 'modify',
            'name' => '修改',
            'addtime' => now(),
            'adduserid' => 1
        ]);
        FunCode::create([
            'status' => 1,
            'code' => 'edit',
            'name' => '编辑',
            'addtime' => now(),
            'adduserid' => 1
        ]);
        FunCode::create([
            'status' => 1,
            'code' => 'enable',
            'name' => '启用',
            'addtime' => now(),
            'adduserid' => 1
        ]);
        FunCode::create([
            'status' => 1,
            'code' => 'disable',
            'name' => '禁用',
            'addtime' => now(),
            'adduserid' => 1
        ]);
        FunCode::create([
            'status' => 1,
            'code' => 'save',
            'name' => '保存',
            'addtime' => now(),
            'adduserid' => 1
        ]);
        FunCode::create([
            'status' => 1,
            'code' => 'submit',
            'name' => '提交',
            'addtime' => now(),
            'adduserid' => 1
        ]);
        FunCode::create([
            'status' => 1,
            'code' => 'upload',
            'name' => '上传',
            'addtime' => now(),
            'adduserid' => 1
        ]);
        FunCode::create([
            'status' => 1,
            'code' => 'download',
            'name' => '下载',
            'addtime' => now(),
            'adduserid' => 1
        ]);
    }
}
