<?php

namespace App\Console\Commands;

use App\Models\Test;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
class MyCMD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cmd:mytask';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '计划任务';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $test = Test::create([
            'hcbh'=>Str::random(15),
            'name'=>'test',
            'certno'=>Str::random(18),
            'fromdb'=>Arr::random([1,2]),
            'addtime'=>now()
        ]);



    }
}
