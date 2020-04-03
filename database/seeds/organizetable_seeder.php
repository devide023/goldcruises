<?php

use App\Models\Organize;
use App\Models\OrganizeType;
use Illuminate\Database\Seeder;

class organizetable_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrganizeType::create([
            'code' => '00',
            'name' => '集团',
            'addtime' => now(),
            'status' => 1
        ]);
        OrganizeType::create([
           'code' => '01',
           'name' => '总公司',
           'addtime' => now(),
           'status' => 1
        ]);
        OrganizeType::create([
            'code' => '02',
            'name' => '分公司',
            'addtime' => now(),
            'status' => 1
        ]);
        OrganizeType::create([
            'code' => '03',
            'name' => '部门',
            'addtime' => now(),
            'status' => 1
        ]);
        $index = 1;
        //
        $root = Organize::create([
            'status'    => 1,
            'orgcode'   => '01',
            'name'      => '重庆长江黄金游轮有限公司',
            'pid'       => 0,
            'orgtype'   => '01',
            'leader'    => '徐斌',
            'adduserid' => \Illuminate\Support\Arr::random([
                11,
                12,
                13,
                14,
                15
            ]),
            'addtime'   => now(),
            'logo'      => 'gold.jpg',
        ]);

        $com1=Organize::create([
            'status'    => 1,
            'orgcode'   => $root->orgcode.str_pad($index,2,'0',STR_PAD_LEFT),
            'name'      => '重庆长江黄金游轮管理分公司',
            'pid'       => $root->id,
            'orgtype'   => '02',
            'leader'    => '代晨林',
            'adduserid' => \Illuminate\Support\Arr::random([
                11,
                12,
                13,
                14,
                15
            ]),
            'addtime'   => now(),
            'logo'      => 'gold1.jpg',
        ]);
        $index++;
        $com2=Organize::create([
            'status'    => 1,
            'orgcode'   => $root->orgcode.str_pad($index,2,'0',STR_PAD_LEFT),
            'name'      => '重庆长江黄金游轮市场营销公司',
            'pid'       => $root->id,
            'orgtype'   => '02',
            'leader'    => '舒苏',
            'adduserid' => \Illuminate\Support\Arr::random([
                11,
                12,
                13,
                14,
                15
            ]),
            'addtime'   => now(),
            'logo'      => 'gold2.jpg',
        ]);
        $index++;
        $dep1=Organize::create([
            'status'    => 1,
            'orgcode'   => $root->orgcode.str_pad($index,2,'0',STR_PAD_LEFT),
            'name'      => '安全管理办公室',
            'pid'       => $root->id,
            'orgtype'   => '03',
            'leader'    => '雷斌',
            'adduserid' => \Illuminate\Support\Arr::random([
                11,
                12,
                13,
                14,
                15
            ]),
            'addtime'   => now(),
            'logo'      => 'gold1.jpg',
        ]);
        $index ++;
        $dep2=Organize::create([
            'status'    => 1,
            'orgcode'   => $root->orgcode.str_pad($index,2,'0',STR_PAD_LEFT),
            'name'      => '营运管理部',
            'pid'       => $root->id,
            'orgtype'   => '03',
            'leader'    => '甘小光',
            'adduserid' => \Illuminate\Support\Arr::random([
                11,
                12,
                13,
                14,
                15
            ]),
            'addtime'   => now(),
            'logo'      => 'gold1.jpg',
        ]);
        $index ++;
        $dep3=Organize::create([
            'status'    => 1,
            'orgcode'   => $root->orgcode.str_pad($index,2,'0',STR_PAD_LEFT),
            'name'      => '办公室',
            'pid'       => $root->id,
            'orgtype'   => '03',
            'leader'    => '张东遇',
            'adduserid' => \Illuminate\Support\Arr::random([
                11,
                12,
                13,
                14,
                15
            ]),
            'addtime'   => now(),
            'logo'      => 'gold1.jpg',
        ]);
        $index ++;
        $dep4=Organize::create([
            'status'    => 1,
            'orgcode'   => $root->orgcode.str_pad($index,2,'0',STR_PAD_LEFT),
            'name'      => '投资发展部',
            'pid'       => $root->id,
            'orgtype'   => '03',
            'leader'    => '',
            'adduserid' => \Illuminate\Support\Arr::random([
                11,
                12,
                13,
                14,
                15
            ]),
            'addtime'   => now(),
            'logo'      => 'gold1.jpg',
        ]);
        $index ++;
        $dep5=Organize::create([
            'status'    => 1,
            'orgcode'   => $root->orgcode.str_pad($index,2,'0',STR_PAD_LEFT),
            'name'      => '财务融资部',
            'pid'       => $root->id,
            'orgtype'   => '03',
            'leader'    => '',
            'adduserid' => \Illuminate\Support\Arr::random([
                11,
                12,
                13,
                14,
                15
            ]),
            'addtime'   => now(),
            'logo'      => 'gold1.jpg',
        ]);
        $index ++;
        $dep6=Organize::create([
            'status'    => 1,
            'orgcode'   => $root->orgcode.str_pad($index,2,'0',STR_PAD_LEFT),
            'name'      => '党群人力部',
            'pid'       => $root->id,
            'orgtype'   => '03',
            'leader'    => '',
            'adduserid' => \Illuminate\Support\Arr::random([
                11,
                12,
                13,
                14,
                15
            ]),
            'addtime'   => now(),
            'logo'      => 'gold1.jpg',
        ]);
        $index =1;
        $dep7=Organize::create([
            'status'    => 1,
            'orgcode'   => $com1->orgcode.str_pad($index,2,'0',STR_PAD_LEFT),
            'name'      => '综合管理部',
            'pid'       => $com1->id,
            'orgtype'   => '03',
            'leader'    => '雷春梅',
            'adduserid' => \Illuminate\Support\Arr::random([
                11,
                12,
                13,
                14,
                15
            ]),
            'addtime'   => now(),
            'logo'      => 'gold1.jpg',
        ]);
        $index ++;
        $dep8=Organize::create([
            'status'    => 1,
            'orgcode'   => $com1->orgcode.str_pad($index,2,'0',STR_PAD_LEFT),
            'name'      => '航务管理部',
            'pid'       => $com1->id,
            'orgtype'   => '03',
            'leader'    => '',
            'adduserid' => \Illuminate\Support\Arr::random([
                11,
                12,
                13,
                14,
                15
            ]),
            'addtime'   => now(),
            'logo'      => 'gold1.jpg',
        ]);
        $index ++;
        $dep9=Organize::create([
            'status'    => 1,
            'orgcode'   => $com1->orgcode.str_pad($index,2,'0',STR_PAD_LEFT),
            'name'      => '酒店管理部',
            'pid'       => $com1->id,
            'orgtype'   => '03',
            'leader'    => '赵静',
            'adduserid' => \Illuminate\Support\Arr::random([
                11,
                12,
                13,
                14,
                15
            ]),
            'addtime'   => now(),
            'logo'      => 'gold1.jpg',
        ]);
        $index ++;
        $dep10=Organize::create([
            'status'    => 1,
            'orgcode'   => $com1->orgcode.str_pad($index,2,'0',STR_PAD_LEFT),
            'name'      => '维修维保部',
            'pid'       => $com1->id,
            'orgtype'   => '03',
            'leader'    => '徐木平',
            'adduserid' => \Illuminate\Support\Arr::random([
                11,
                12,
                13,
                14,
                15
            ]),
            'addtime'   => now(),
            'logo'      => 'gold1.jpg',
        ]);
    }
}
