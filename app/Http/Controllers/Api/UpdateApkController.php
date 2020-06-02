<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Update;
use Illuminate\Http\Request;

class UpdateApkController extends Controller
{
    //
    public function update(Request $request)
    {
        try
        {
            $appid = $request->appid;
            $version = $request->version;
            $entity = Update::where('appid', $appid)->first();
            if ($entity->version != $version)
            {
                return [
                    'code' => 1,
                    'msg'  => '有新版本' . $entity->version,
                    'url'  => asset('/storage/' . $entity->apkfilename)
                ];
            } else
            {
                return [
                    'code'    => 0,
                    'msg'     => '暂无新版本',
                    'version' => $version
                ];
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
