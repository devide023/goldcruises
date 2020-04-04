<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TestController extends Controller
{
    //
    public function test(Request $request)
    {
        try
        {
           $pwd = hash('sha256','123456');
           $pwd1 = Hash::make('123456');
           var_dump($pwd);
           var_dump($pwd1);

        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }
}
