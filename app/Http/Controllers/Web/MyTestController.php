<?php

namespace App\Http\Controllers\Web;

use App\Code\BaiduToken;
use App\Code\MyPost;
use App\Code\UserTrail;
use App\Code\WeChat;
use App\Http\Controllers\Controller;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Redis;
use Illuminate\Support\Facades\Storage;

class MyTestController extends Controller
{
    //
    use WeChat;
    use MyPost;
    //use BaiduToken;
    use UserTrail;

    public function info()
    {
        $hosts = [
            '192.168.20.89:9200',
        ];
        $client = ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($hosts)      // Set the hosts
        ->build();
        $params = [
            'index' => 'chengssion',
            //'id' => 1, #可以手动指定id，也可以不指定随机生成
            'body' => [
                'first_name' => '张',
                'last_name' => '三',
                'age' => 35
            ]
        ];
        $res = $client->index($params);
        return $res;
    }

    public function redistest()
    {
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6380);
        $v = $redis->set('k1', 'v1');
        var_dump($v);
    }

    public function html_text(Request $request)
    {
        try {
            $data = $this->usermenubyid(Auth::id());
            return $data;
            $yzm = '';
            do {
                $imgurl = 'http://www.yzlcq.com/web/login/checklogin.aspx';
                $token = $this->fresh_baidu_accesstoken();
                $apiurl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/general_basic?access_token=' . $token;
                $img = file_get_contents($imgurl);
                Storage::put('code.jpeg', $img);
                $img = base64_encode($img);
                $bodys = array(
                    'image' => $img
                );
                $res = $this->RequestPost($apiurl, $bodys);

                dd($res);

                $code = json_decode($res)->words_result;
                $yzm = $code[0]->words;
            } while (strlen($yzm) != 4);
            $formaction = 'https://kc.3xylly.com/cruise-login/oauth/authorize';
            $loginresult = $this->RequestPost($formaction, [
                'client_id' => 'webcruise',
                'response_type' => 'code',
                'scope' => 'read write',
                'state' => 'state1',
                'redirect_uri' => 'https://kc.3xylly.com/cruise-web/loginsuccess',
                'client_secret' => '',
                'username' => 'cjhj06',
                'password' => 'pXSTLk4av5Z6uNfcCFmlgZFxyqMT2f0F0UKnwyPgO4w2P6SGYsSgGiMb+c1usgUxCHQ1L0a2Db03s7pu6pMenv/9PHTzcOXZCyB4/8ZkqC5PKpCv0Tp6z99CMgMCBiPYjeHQ+WoM3lBlcb/V+KaZfdyygrV4C5nhLxjaKd2f6QM66hGiM6cmuJUgMoqHCUvtLSulPKawjrxkY43M8CV/ws3SFs6Lf+ZE10yQ0pfklRUtw5Fqh/tKktzkEaY7Ti5EWb/11NjsinFz0na3hSnKTSnx1MJp0cQlJdX9L6DGPl3QmxN2o4c/obIBiCVQQYbsVMIUP8xC2eU4Xt/8quYxcQ==',
                'validcode' => $yzm
            ]);
            var_dump($loginresult);
            $maiurl = 'https://kc.3xylly.com/cruise-web/cruiseplan/index/0';
            dd($yzm);

        } catch (Exception $exception) {
            return [
                'code' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }
}
