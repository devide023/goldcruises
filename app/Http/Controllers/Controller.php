<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Request;

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

    public function UnAutherror()
    {
        return  [
            'code'   => 0,
            'msg'    => '未授权请登录或联系管理员',
        ];
    }

    public function ParamError()
    {
        return [
            'code'=>0,
            'msg'=>'参数错误',
        ];
    }


}
