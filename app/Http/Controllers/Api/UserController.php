<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function list(Request $request)
    {
        try
        {
            $query = User::query();
            $query->when($request->username, function (Builder $q) use ($request)
            {
                $q->where('name', 'like', '%' . $request->username . '%');
            });
            $query->when($request->address, function (Builder $q) use ($request)
            {
                $q->where('address', 'like', '%' . $request->address . '%');
            });
            $query->when($request->idno, function (Builder $q) use ($request)
            {
                $q->where('idno', 'like', '%' . $request->idno . '%');
            });
            $query->when($request->tel, function (Builder $q) use ($request)
            {
                $q->where('tel', 'like', '%' . $request->tel . '%');
            });
            $query->when($request->email, function (Builder $q) use ($request)
            {
                $q->where('email', 'like', '%' . $request->email . '%');
            });
            return $query->with([
                'adduser:id,name',
                'sexname:code,name',
                'provincename:code,name',
                'cityname:code,name',
                'districtname:code,name'
            ])->get();
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function login(Request $request)
    {
        try
        {
            $usercode = $request->username;
            $userpwd = $request->userpwd;
            $user = User::where('usercode', $usercode)->first();
            if (!is_null($user))
            {
                $pwd = decrypt($user->userpwd);
                if ($pwd == $userpwd)
                {
                    return [
                        'code'  => 1,
                        'msg'   => "ok",
                        'token' => $user->api_token
                    ];
                } else
                {
                    return [
                        'code'  => 0,
                        'msg'   => "密码错误！",
                        'token' => null
                    ];
                }
            } else
            {
                return [
                    'code'  => 0,
                    'msg'   => "用户名错误！",
                    'token' => null
                ];
            }
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

}
