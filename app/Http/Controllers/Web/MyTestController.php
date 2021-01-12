<?php

namespace App\Http\Controllers\Web;

use App\Code\BaiduToken;
use App\Code\MyPost;
use App\Code\UserTrail;
use App\Code\WeChat;
use App\Http\Controllers\Controller;
use App\Models\HotelBook;
use App\Models\HotelBookDetail;
use App\Models\User;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Schema;
use PhpParser\Node\Stmt\TryCatch;
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
        return phpinfo();
    }

    public function hotelbook()
    {
        $cols = Schema::getColumnListing('hotelbook');
        $hotel = HotelBook::query();
        $list = $hotel->get();
        foreach ($list as $item) {
            $key = 'hotelbook:' . $item->id;
            $temp = [];
            $changecols = ['bdate', 'edate', 'addtime'];
            foreach ($cols as $col) {
                if (in_array($col, $changecols, true)) {
                    $t = date('Y-m-d H:i:s', strtotime($item->bdate));
                    $temp[$col] = $t;
                } else {
                    $temp[$col] = $item->$col;
                }
            }
            Redis::hmset($key, $temp);
        }
        //
        $cols = Schema::getColumnListing('hotelbookdetail');
        $detail = HotelBookDetail::query();
        $list = $detail->get();
        foreach ($list as $item) {
            $key = 'hotelbookdetail:' . $item->id;
            $temp = [];
            foreach ($cols as $col) {
                $temp[$col] = $item->$col;
            }
            Redis::hmset($key, $temp);
        }
    }

    public function gethotelbook(Request $request)
    {
        $id = $request->id ?? 0;
        $key = 'hotelbook:' . $id;
        return Redis::hgetall($key);
    }

    public function msg()
    {
        Redis::publish('c1', 'hello world');
    }

    public function finduser(Request $request)
    {
        $uid = $request->id ?? 1;
        $res = Redis::hgetall('user:' . $uid);
        return $res;
    }

    public function filminfo(Request $request)
    {
        $id = $request->id??0;
        Redis::select(1);
        $films = Redis::hgetall('film:'.$id);
        return $films;
    }
    public function redistest()
    {
        try {
            $user = User::query();
            $list = $user->get();
            $cols = \Schema::getColumnListing('user');

            foreach ($list as $item) {
                $key = 'user:' . $item->id;
                $temp = [];
                foreach ($cols as $col) {
                    $temp[$col] = $item->$col;
                }
                Redis::hmset($key, $temp);
            }


        } catch (Exception $exception) {
            return [
                'code' => 0,
                'msg' => $exception->getMessage()
            ];
        }
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
