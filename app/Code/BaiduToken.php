<?php


namespace App\Code;


use App\Models\AccessToken;

trait BaiduToken
{
    use  MyPost;
    public function baidutoken(){
        $tokenurl = 'https://aip.baidubce.com/oauth/2.0/token';
        $post_data['grant_type']       = 'client_credentials';
        $post_data['client_id']      = 'wT5biaTk3bWLZV09gypw7azb';
        $post_data['client_secret'] = 'XPHzxhVlhYUGPyceIfDNg1QmZnEs9Nf8';
        $o = "";
        foreach ( $post_data as $k => $v )
        {
            $o.= "$k=" . urlencode( $v ). "&" ;
        }
        $post_data = substr($o,0,-1);

        $result = $this->RequestPost($tokenurl, $post_data);
        $obj = json_decode($result);
        return $obj;
    }

    public function fresh_baidu_accesstoken()
    {
        try
        {
            $cnt = AccessToken::where('type', '=', 'baidu')->count();

            if ($cnt == 0)
            {
                $result = $this->baidutoken();
                AccessToken::create([
                    'access_token' => $result->access_token,
                    'addtime'      => now(),
                    'type'         => 'baidu'
                ]);
                return $result->access_token;
            } else
            {
                $token = AccessToken::where('type', '=', 'baidu')->first();
                $tokentime = strtotime($token->addtime);
                $overtime = date('Y-m-d H:i:s', strtotime('+ 1 month', $tokentime));
                if (date_create($overtime) < now())
                {
                    //access_token过期
                    $result = $this->baidutoken();
                    $token->update([
                        'access_token' => $result->access_token,
                        'addtime'      => now(),
                        'type'         => 'baidu'
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
}
