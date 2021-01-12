<?php

namespace App\Http\Controllers\Redis;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class RedisController extends Controller
{
    private $redis;
    public function __construct()
    {
        $host = env('REDIS_HOST');
        $pwd = env('REDIS_PASSWORD');
        $port = env('REDIS_PORT');
        $this->redis = new \Redis();
        $this->redis->connect($host,$port);
        $this->redis->auth($pwd);
    }

    public function index()
    {
        Redis::subscribe(['c1'],function($msg){
            echo $msg;
        });
    }

    public function userinfo()
    {
        $ret = $this->redis->set('user:1','{name:abc}');
        return $ret;
    }

}
