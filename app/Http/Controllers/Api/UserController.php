<?php

namespace App\Http\Controllers\Api;

use App\Code\UserTrail;
use App\Code\Utils;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Organize;
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
    use Utils;
    use  UserTrail;
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
                'headimg'   => $request->headimg ?? 'default_head.jpg',
                'api_token' => \hash('sha256', Str::random(50)),
            ]);
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
            $roleids = $request->roleids;
            $user->roles()->sync($roleids);
            return $this->success();
        } catch (Exception $exception)
        {

        }
    }

    public function getuserroles(Request $request)
    {
        try
        {
            $user = User::find($request->id);
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $user->roles
            ];
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    /***
     * @param Request $request
     * @return array
     * 保存用户关联组织
     */
    public function userorgs(Request $request)
    {
        try
        {
            $user = User::find($request->id);
            $nodes = collect($request->orgnodes);
            $postdata = [];
            foreach ($nodes as $node)
            {
                $postdata[$node['id']] = [
                    'adduserid' => Auth::id(),
                    'addtime'   => now(),
                    'main'      => 0,
                    'companyid' => $node['parentid']
                ];
            }
            $user->orgnodes()->sync($postdata);
            return $this->success();
        } catch (Exception $exception)
        {

        }
    }

    public function getuserorg(Request $request)
    {
        try
        {
            $user = User::find($request->id);
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $user->orgnodes
            ];
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function save_userpermission(Request $request)
    {
        try
        {
            $orgids = $request->orgids;
            $user = User::find($request->id);
            $postdata = [];
            foreach ($orgids as $orgid)
            {
                array_push($postdata, [
                    'orgid'     => $orgid['id'],
                    'adduserid' => Auth::id(),
                    'addtime'   => now()
                ]);
            }
            $ok = $user->permissions()->delete();
            if (count($orgids) > 0)
            {
                $cnt = $user->permissions()->createMany($postdata);
                if (count($cnt) == count($orgids))
                {
                    return $this->success();
                } else
                {
                    return $this->error();
                }
            } else
            {
                return $this->success();
            }

        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }

    }

    public function get_userpermission(Request $request)
    {
        try
        {
            $userid = $request->id ?? 0;
            $orgids = DB::table('userpermission')->where('userid', $userid)->pluck('orgid');
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $orgids
            ];
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
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
                    'adress'    => $request->adress,
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

    public function changepwd(Request $request)
    {
        try
        {
            $userid = $request->userid ?? 0;
            $user = User::find($userid);
            if ($userid > 0)
            {
                $oldpwd = $request->oldpwd;
                $newpwd = $request->newpwd;
                $enoldpwd = \hash('sha256', $oldpwd);
                if ($enoldpwd == $user->userpwd)
                {
                    $ok = $user->update([
                        'userpwd' => \hash('sha256', $newpwd)
                    ]);
                    if ($ok)
                    {
                        return $this->success();
                    } else
                    {
                        return $this->error();
                    }
                } else
                {
                    return [
                        'code' => 0,
                        'msg'  => '密码错误'
                    ];
                }
            } else
            {
                return $this->error();
            }
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
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

    public function findbyid(Request $request)
    {
        try
        {
            $user = User::find($request->id);
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $user
            ];
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function enable(Request $request)
    {
        try
        {
            $users = User::where('status', '=', 0)->whereIn('id', $request->ids);
            $cnt = $users->update([
                'status' => 1
            ]);
            if ($cnt > 0)
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

    public function disable(Request $request)
    {
        try
        {
            $users = User::where('status', '=', 1)->whereIn('id', $request->ids);
            $cnt = $users->update([
                'status' => 0
            ]);
            if ($cnt > 0)
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
            $deptids = DB::table('userorg')->where('userid', $user->id)->pluck('departmentid');
            $comids = DB::table('userorg')->where('userid', $user->id)->pluck('companyid');
            $depid = count($deptids) > 0 ? $deptids[0] : 0;
            $organize = Organize::find($depid);
            $orgtype = !is_null($organize) ? $organize->orgtype : '';
            $user['headimg'] = asset('/storage/' . $user->headimg);
            $user['companyid'] = count($comids) > 0 ? $comids[0] : 0;
            $user['orgid'] = count($deptids) > 0 ? $deptids[0] : 0;
            $user['orgtype'] = $orgtype;
            $user['userid'] = $user->id;
            $request['id'] = $user->id;
            $menulist = $this->getusermenus($request);
            return [
                'code'     => 1,
                'user'     => $user,
                'menulist' => $menulist
            ];
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function chinfo(Request $request)
    {
        try
        {
            $ok = User::where('usercode', $request->usercode)->update([
                'name'      => $request->name,
                'sex'       => $request->sex,
                'tel'       => $request->tel,
                'email'     => $request->email,
                'adress'    => $request->adress,
                'province'  => $request->province,
                'city'      => $request->city,
                'district'  => $request->district,
                'idno'      => $request->idno,
                'birthdate' => $request->birthdate
            ]);
            if ($ok > 0)
            {
                return $this->success();
            } else
            {
                return $this->error();
            }
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
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
                if (!is_null($request->userid))
                {
                    User::find($request->userid)->update([
                        'headimg' => $filename
                    ]);
                }
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
        return  $this->usermenubyid($request->id);
    }

    /***
     * @param Request $request
     * 获取用户路由表
     */
    public function userroutes(Request $request)
    {
        try
        {
            $route = $this->get_current_user_route();
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $route
            ];

        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

}
