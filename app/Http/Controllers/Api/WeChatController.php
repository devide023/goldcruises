<?php

namespace App\Http\Controllers\Api;

use App\Code\WeChatConfig;
use App\Http\Controllers\Controller;
use App\Models\Wechat;
use Illuminate\Http\Request;

class WeChatController extends Controller
{
    use WeChatConfig;

    //
    public function login(Request $request)
    {
        try
        {
            $code = $request->code;
            $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . self::$APPID . '&secret=' . self::$secret . '&js_code=' . $code . '&grant_type=authorization_code';
            $result = \GuzzleHttp\json_decode(file_get_contents($url));
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $result
            ];

        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

    public function addwechatinfo(Request $request)
    {
        try
        {
            $openid = $request->openid;
            $has = Wechat::where('openid', $openid)->count();
            if ($has == 0)
            {
                $wechat = Wechat::create([
                    'openid'    => $request->openid,
                    'nickname'  => $request->nickname,
                    'avatarUrl' => $request->avatarurl,
                    'gender'    => $request->gender,
                    'province'  => $request->province,
                    'city'      => $request->city,
                    'addtime'   => now()
                ]);
                if ($wechat->id > 0)
                {
                    return $this->success();
                } else
                {
                    return $this->error();
                }
            } else
            {
                $cnt = Wechat::where('openid', $openid)->update([
                    'nickname'  => $request->nickname,
                    'avatarUrl' => $request->avatarurl,
                    'gender'    => $request->gender,
                    'province'  => $request->province,
                    'city'      => $request->city,
                ]);
                if ($cnt > 0)
                {
                    return $this->success();
                } else
                {
                    return $this->error();
                }
            }

        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

    public function updatewechatinfo(Request $request)
    {
        try
        {
            $openid = $request->openid;
            $cnt = Wechat::where('openid', $openid)->update([
                'userid' => $request->userid,
            ]);
            if ($cnt > 0)
            {
                return $this->success();
            } else
            {
                return $this->error();
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
