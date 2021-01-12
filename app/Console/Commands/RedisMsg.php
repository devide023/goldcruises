<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class RedisMsg extends Command
{
    protected $signature = 'redis:msg';

    protected $description = 'Redis订阅发布';

    public function handle()
    {
        //
        Redis::subscribe(['c1'],function($msg){
            echo $msg;
        });
    }
}
