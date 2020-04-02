<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    //
    public function test(Request $request)
    {
        try
        {
           $pwd = hash('sha256','123456');
           var_dump($pwd);

        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }
}
