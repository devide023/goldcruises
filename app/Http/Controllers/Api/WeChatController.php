<?php

namespace App\Http\Controllers\Api;

use App\Code\WeChatConfig;
use App\Http\Controllers\Controller;
use App\Models\AccessToken;
use App\Models\User;
use App\Models\Wechat;
use Illuminate\Http\Request;
use Psy\TabCompletion\Matcher\VariablesMatcher;

class WeChatController extends Controller
{
    use WeChatConfig;
    use \App\Code\WeChat;

    //
    public function login(Request $request)
    {
        try
        {
            $code = $request->code;
            $usercode = $request->usercode ?? '';
            $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . self::$APPID . '&secret=' . self::$secret . '&js_code=' . $code . '&grant_type=authorization_code';

            $result = json_decode(file_get_contents($url));
            if (!empty($usercode) && !is_null($result->openid) && !is_null($result->session_key))
            {
                User::where('usercode', $usercode)->update([
                    'openid'      => $result->openid,
                    'session_key' => $result->session_key
                ]);
            }
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $result->openid
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

    public function fresh_mp_accesstoken(Request $request)
    {
        try
        {
            return $this->fresh_accesstoken('mp', self::$APPID, self::$secret);
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

    public function fresh_gzh_accesstoken(Request $request)
    {
        try
        {
            return $this->fresh_accesstoken('gzh', self::$gzhAPPID, self::$gzhsecret);
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

    public function get_gzhmenu(Request $request)
    {
        try
        {
            $token = $this->fresh_accesstoken('gzh', self::$gzhAPPID, self::$gzhsecret);
            $url = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token=' . $token;
            var_dump($url);
            $res = file_get_contents($url);
            $result = json_decode($res);
            return $result;

        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

    public function gzh_menu(Request $request)
    {
        try
        {
            $token = $this->fresh_accesstoken('gzh', self::$gzhAPPID, self::$gzhsecret);
            $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $token;
            $data = [
                'button' => [
                    [
                        'name' => 'a',
                        'type' => 'view',
                        'url'  => 'http://www.baidu.com'
                    ],
                    [
                        'name'       => 'b',
                        'sub_button' => [
                            [
                                'type'     => 'miniprogram',
                                'name'     => 'wxa',
                                'url'      => 'http://mp.weixin.qq.com',
                                'appid'    => self::$APPID,
                                'pagepath' => 'pages/login/login'
                            ]
                        ]
                    ]
                ]
            ];
            return $data;
            //$res = $this->request_post($url,$data);
            //$result = json_decode($res);

        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

    public function checkSignature(Request $request)
    {
        $signature = $request->signature;
        $timestamp = $request->timestamp;
        $nonce = $request->nonce;
        $token = self::$token;
        $tmpArr = array(
            $token,
            $timestamp,
            $nonce
        );
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature)
        {
            return $request->echostr;
        } else
        {
            return false;
        }
    }

    public function send_wechat_msg(Request $request)
    {
        try
        {
            $billid = $request->billid ?? 0;
            if ($billid > 0)
            {
                return $this->sendmsg($billid);
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

    //将字符串写入文件
    function put_to_file($file, $content)
    {
        $fopen = fopen($file, 'a');
        if (!$fopen)
        {
            return false;
        }
        fwrite($fopen, $content);
        fclose($fopen);
        return true;
    }
}
