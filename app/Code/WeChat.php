<?php


namespace App\Code;


use App\Models\AccessToken;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait WeChat
{
    use WeChatConfig;

    public function request_post($url = '', $data = null)
    {
        $headers = array(
            'Content-type:application/json;charset=UTF-8',
            'Accept:application/json',
            'Cache-Control:no-cache',
            'Pragma:no-cache'
        );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if (!is_null($data))
        {
            $data = json_encode($data);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    public function sendmsg($bill_id)
    {
        try
        {
            $sendret = [];
            $querylist = collect(DB::select('call openids_process_billid(1,' . $bill_id . ')'));
            $accesstoken = $this->fresh_accesstoken('mp', self::$APPID, self::$secret);
            $tempid = 'V66ABlmM_Fqdqag9sqM9LZHCfp7Dxw991gUDeSPffiY';
            $url = 'https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=' . $accesstoken;

            foreach ($querylist as $item)
            {
                $auditcnt = count($this->user_audit_ids(1, $item->id));
                $request_data = [
                    'touser'            => $item->openid,
                    'template_id'       => $tempid,
                    'page'              => 'pages/login/login',
                    //本消息点击后跳转到的页面
                    'data'              => [
                        'number1' => [
                            'value' => $auditcnt
                        ],
                        'time2'   => [
                            'value' => date('Y-m-d H:i:s')
                        ]
                    ],
                    'miniprogram_state' => 'trial'
                ];
                $return = $this->request_post($url, $request_data);
                array_push($sendret,$return);
            }
            return [
              'code'=>1,
              'msg'=>'消息发送成功',
              'result'=>$sendret
            ];

        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

    public function fresh_accesstoken($tokentype = 'mp', $appid, $secret)
    {
        try
        {
            $cnt = AccessToken::where('type', '=', $tokentype)->count();

            if ($cnt == 0)
            {
                $result = $this->accesstoken($appid, $secret);
                AccessToken::create([
                    'access_token' => $result->access_token,
                    'addtime'      => now(),
                    'type'         => $tokentype
                ]);
                return $result->access_token;
            } else
            {
                $token = AccessToken::where('type', '=', $tokentype)->first();
                $tokentime = strtotime($token->addtime);
                $overtime = date('Y-m-d H:i:s', strtotime('+ 2 hour', $tokentime));
                if (date_create($overtime) < now())
                {
                    //access_token过期
                    $result = $this->accesstoken($appid, $secret);
                    $token->update([
                        'access_token' => $result->access_token,
                        'addtime'      => now(),
                        'type'         => $tokentype
                    ]);
                    return $result->access_token;
                } else
                {
                    return $token->access_token;
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

    public function accesstoken($appid, $pwd)
    {
        try
        {
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $appid . '&secret=' . $pwd;
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
}
