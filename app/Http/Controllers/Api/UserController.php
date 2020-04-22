<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class UserController extends Controller
{

    public function list(Request $request)
    {
        try
        {
            $pagesize = $request->pagesize ?? 15;
            $query = User::query();
            $query->when(!is_null($request->key), function (Builder $q) use ($request)
            {
                return $q->where(function (Builder $s) use ($request)
                {
                    return $s->where('usercode', 'like', '%' . $request->key . '%')
                        ->orWhere('name', 'like', '%' . $request->key . '%')
                        ->orWhere('tel', 'like', '%' . $request->key . '%')
                        ->orWhere('adress', 'like', '%' . $request->key . '%')
                        ->orWhere('idno', 'like', '%' . $request->key . '%')
                        ->orWhere('email', 'like', '%' . $request->key . '%');
                });
            });
            $query->when(!is_null($request->ksrq) && !is_null($request->jsrq), function (Builder $q) use ($request)
            {
                $v1 = $request->ksrq . ' 0:0:0';
                $v2 = $request->jsrq . ' 23:59:59';
                return $q->whereBetween('addtime', [
                    $v1,
                    $v2
                ]);
            });
            $query->when($request->username, function (Builder $q) use ($request)
            {
                return $q->where('name', 'like', '%' . $request->username . '%');
            });
            $query->when($request->address, function (Builder $q) use ($request)
            {
                return $q->where('address', 'like', '%' . $request->address . '%');
            });
            $query->when($request->idno, function (Builder $q) use ($request)
            {
                return $q->where('idno', 'like', '%' . $request->idno . '%');
            });
            $query->when($request->tel, function (Builder $q) use ($request)
            {
                return $q->where('tel', 'like', '%' . $request->tel . '%');
            });
            $query->when($request->email, function (Builder $q) use ($request)
            {
                return $q->where('email', 'like', '%' . $request->email . '%');
            });
            //var_dump($query->toSql());
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $query->paginate($pagesize)
            ];
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
            DB::beginTransaction();
            $user = User::create([
                    'status'    => $request->status,
                    'usercode'  => $request->usercode,
                    'sex'       => $request->sex,
                    'name'      => $request->username,
                    'userpwd'   => \hash('sha256', $request->password),
                    'birthdate' => $request->birthday,
                    'idno'      => $request->idno,
                    'tel'       => $request->tel,
                    'adress'    => $request->address,
                    'email'     => $request->email,
                    'province'  => $request->province,
                    'city'      => $request->city,
                    'district'  => $request->district,
                    'adduserid' => Auth::user()->id,
                    'addtime'   => now(),
                    'headimg'   => $request->headimg,
                    'api_token' => \hash('sha256', Str::random(50)),
                ]);
                $nodes = $request->organizeids;
                foreach ($nodes as $node){
                    $user->orgnodes()->attach($node[count($node)-1], [
                        'adduserid' => Auth::id(),
                        'addtime'   => now(),
                        'main'      => 0,
                        'companyid'=>$node[count($node)-2],
                        'groupid'=>$node[0]
                    ]);
                }

            if ($user->id > 0)
            {
                DB::commit();
                return [
                    'code'   => 1,
                    'msg'    => 'ok',
                    'result' => $user
                ];
            } else
            {
                DB::rollBack();
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

    public function userroles(Request $request)
    {
        try
        {
            $user = User::find($request->id);
            $roleids = $request->roleid;
            $user->roles()->sync($roleids);
            return $this->success();
        } catch (Exception $exception)
        {

        }
    }

    public function userorgs(Request $request)
    {
        try
        {
            $user = User::find($request->id);
            $orgids = $request->orgid;
            $attr = [
                'main'      => 0,
                'adduserid' => Auth::user()->id,
                'addtime'   => now()
            ];
            $postdata = [];
            foreach ($orgids as $orgid)
            {
                $postdata[$orgid] = $attr;
            }
            $postdata[Arr::random($orgids)]['main'] = 1;
            $user->orgnodes()->sync($postdata);
            return $this->success();
        } catch (Exception $exception)
        {

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
                $isok = $user->update([
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
            } else
            {
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

    public function del(Request $request)
    {
        try
        {
            $uid = $request->id;
            if (!is_null($uid))
            {
                DB::transaction(function () use ($request)
                {
                    $user = User::find($request->id);
                    $user->delete();
                    $user->roles()->detach();
                    $user->orgnodes()->detach();
                });
                return $this->success();
            } else
            {
                return [
                    'code' => 0,
                    'msg'  => '参数错误',
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
                $user = User::where('usercode', $usercode)->first();
                if (is_null($user))
                {
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
            $user = User::where('usercode', '=', $usercode)->first();
            $pwdnew = \hash('sha256', $request->newpassword);
            if (!is_null($user) && $user->userpwd == \hash('sha256', $request->password))
            {
                $isok = $user->update([
                    'userpwd' => $pwdnew
                ]);
                if ($isok)
                {
                    return parent::success();
                } else
                {
                    return parent::error();
                }
            } else
            {
                return [
                    'code'   => 0,
                    'msg'    => '密码不正确',
                    'result' => null
                ];
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
            $userpwd = $request->password;
            $user = User::where('usercode', $usercode)->first();
            if (!is_null($user))
            {
                $pwd = hash('sha256', $userpwd);
                if ($pwd == $user->userpwd)
                {
                    $request['id'] = $user->id;
                    $menulist = $this->getusermenus($request);
                    return [
                        'code'     => 1,
                        'msg'      => "ok",
                        'token'    => $user->api_token,
                        'menulist' => $menulist
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

    public function findbyid(Request $request)
    {
        try
        {
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => User::find($request->id)
            ];
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function info(Request $request)
    {
        try
        {
            $user = User::where('id', Auth::id())->select([
                'id',
                'name',
                'idno',
                'adress',
                'tel',
                'email',
                'headimg'
            ])->first();
            $user['headimg'] = asset('/storage/' . $user->headimg);
            return [
                'code' => 1,
                'user' => $user
            ];
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function logout(Request $request)
    {
        try
        {
            $user = Auth::user();
            $isok = $user->update([
                'api_token' => \hash('sha256', Str::random(60)),
            ]);
            if ($isok)
            {
                return $this->success();
            } else
            {
                return $this->error();
            }
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function upload_headimg(Request $request)
    {
        try
        {
            $file = $request->file('file');
            if (!$file->isValid())
            {
                return [
                    'code' => 0,
                    'msg'  => '文件上传错误'
                ];
            }
            $tmpFile = $file->getRealPath();
            if (filesize($tmpFile) >= 2048000)
            {
                return [
                    'code' => 0,
                    'msg'  => '文件超过2M!'
                ];
            }
            $fileExtension = $file->clientExtension();
            $filename = Uuid::uuid1()->getHex() . '.' . $fileExtension;
            if (!in_array($fileExtension, [
                'png',
                'jpg',
                'jpeg'
            ]))
            {
                return [
                    'code' => 0,
                    'msg'  => '非法的文件格式'
                ];
            }
            $ok = Storage::disk('local')->put('/public/' . $filename, file_get_contents($tmpFile));
            if ($ok)
            {
                return [
                    'code'     => 1,
                    'msg'      => '文件上传成功',
                    'filename' => $filename,
                    'filepath' => asset('/storage/' . $filename)
                ];
            } else
            {
                return [
                    'code'     => 0,
                    'msg'      => '文件上传失败',
                    'filename' => '',
                    'filepath' => '#'
                ];
            }
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    /**
     * @param Request $request
     * @return array
     * 获取用户的菜单
     */
    public function getusermenus(Request $request)
    {
        $menu = DB::table('roleuser')->where('roleuser.userid', $request->id)
            ->join('rolemenu', 'roleuser.roleid', '=', 'rolemenu.roleid')
            ->join('menu', 'rolemenu.menuid', '=', 'menu.id')->select([
                'menu.id',
                'menu.pid',
                'menu.menucode as code',
                'menu.name as title',
                'menu.menutype',
                'menu.path',
                'menu.viewpath',
                'menu.icon',
            ])->where('menu.status', '=', 1)->distinct()->get();
        return $menu;
    }

}
