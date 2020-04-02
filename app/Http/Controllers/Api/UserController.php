<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

    public function add(Request $request)
    {
        try
        {
            $list = User::where('usercode', $request->usercode)->get();
            if ($list->count() > 0)
            {
                return [
                    'code'   => 0,
                    'msg'    => '用户编码已存在',
                    'result' => null
                ];
            }
            $user = User::create([
                'status'    => $request->status,
                'usercode'  => $request->usercode,
                'sex'       => $request->sex,
                'name'      => $request->username,
                'userpwd'   => \hash('sha256',$request->password),
                'birthdate' => $request->birthday,
                'idno'      => $request->idno,
                'tel'       => $request->tel,
                'adress'    => $request->address,
                'email'     => $request->email,
                'province'  => $request->province,
                'city'      => $request->city,
                'district'  => $request->district,
                'adduserid' => $request->adduserid,
                'addtime'   => now(),
                'headimg'   => $request->headimg,
                'api_token' => \hash('sha256', Str::random(50)),
            ]);
            if ($user->id > 0)
            {
                return [
                    'code'   => 1,
                    'msg'    => 'ok',
                    'result' => $user
                ];
            } else
            {
                return [
                    'code'   => 0,
                    'msg'    => 'error',
                    'result' => null
                ];
            }

        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function edit(Request $request)
    {
        try
        {
            $usercode = $request->usercode;
            if (is_null($usercode))
            {
                return [
                    'code'   => 0,
                    'msg'    => '用户编码不存在',
                    'result' => null
                ];
            }
            $user = User::where('usercode', $usercode)->first();
            if (!is_null($user))
            {
                $isok = $user->save([
                    'status'    => $request->status,
                    'sex'       => $request->sex,
                    'name'      => $request->username,
                    'birthdate' => $request->birthday,
                    'idno'      => $request->idno,
                    'tel'       => $request->tel,
                    'adress'    => $request->address,
                    'email'     => $request->email,
                    'province'  => $request->province,
                    'city'      => $request->city,
                    'district'  => $request->district,
                ]);
                if ($isok)
                {
                    return [
                        'code' => 1,
                        'msg'  => '操作成功'
                    ];
                } else
                {
                    return [
                        'code' => 0,
                        'msg'  => '操作失败',
                    ];
                }
            }else{
                return [
                    'code' => 0,
                    'msg'  => '操作失败',
                ];
            }
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function modifypwd(Request $request)
    {
        try
        {
            $usercode = $request->usercode;
            if (is_null($usercode))
            {
                $user = User::where('usercode',$usercode)->first();
                if(is_null($user)){
                    return [
                        'code'   => 0,
                        'msg'    => '用户编码不存在',
                        'result' => null
                    ];
                }
                return [
                    'code'   => 0,
                    'msg'    => '用户编码不存在',
                    'result' => null
                ];
            }
            $user = User::where('usercode',$usercode)->first();
            if($user->userpwd == $request->password){
                $isok = $user->save([
                   'userpwd'=>\hash('sha256',$request->newpassword)
                ]);
                if($isok){
                    parent::success();
                }else{
                    parent::error();
                }
            }
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
