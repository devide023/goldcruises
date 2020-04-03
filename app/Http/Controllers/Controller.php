<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function error(){
        return [
            'code'   => 0,
            'msg'    => '数据操作失败',
        ];
    }

    public function success(){
        return  [
            'code'   => 1,
            'msg'    => '数据操作成功',
        ];
    }
}
