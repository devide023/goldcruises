<?php

namespace App\Http\Controllers\Api;

use App\Code\Utils;
use App\Http\Controllers\Controller;
use App\Models\FunCode;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    use Utils;
    //

    public function list(Request $request)
    {
        try
        {
            $pagesize = $request->pagesize ?? 15;
            $query = Menu::query();
            $query->when(!is_null($request->pid), function (Builder $q) use ($request)
            {
                return $q->where('pid', $request->pid);
            });
            $query->when(!is_null($request->key), function (Builder $q) use ($request)
            {
                return $q->where(function (Builder $s) use ($request)
                {
                    return $s->where('name', 'like', '%' . $request->key . '%')
                        ->orWhere('menucode', 'like', '%' . $request->key . '%')
                        ->orWhere('note', 'like', '%' . $request->key . '%')
                        ->orWhere('menutype', 'like', '%' . $request->key . '%');
                });
            });
            $query->when($request->name, function (Builder $q) use ($request)
            {
                return $q->where('name', 'like', '%' . $request->name . '%');
            });
            $query->when($request->menucode, function (Builder $q) use ($request)
            {
                return $q->where('menucode', $request->menucode);
            });
            $query->when($request->menutype, function (Builder $q) use ($request)
            {
                return $q->where('menutype', $request->menutype);
            });
            $query->when(!is_null($request->ksrq) && !is_null($request->jsrq), function (Builder $q) use ($request)
            {
                return $q->whereBetween('addtime', [
                    $request->ksrq . ' 00:00:00',
                    $request->jsrq . ' 23:59:59'
                ]);
            });
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
            $codes = $request->funcodes;
            if ($request->menutype == '03' && count($codes) > 0)
            {
                $mcode = $request->menucode;
                $temp = str_split($mcode, 2);
                $idex = $temp[count($temp) - 1];
                $lastint = (int)$idex;
                $cnt = [];
                foreach ($codes as $code)
                {
                    $has = Menu::where('name', $code)->where('pid', $request->pid)->where('menutype', '=', '03')
                        ->count();
                    if ($has == 0)
                    {
                        $menu = Menu::create([
                            'pid'       => $request->pid,
                            'name'      => $code,
                            'menucode'  => $mcode,
                            'menutype'  => $request->menutype,
                            'adduserid' => Auth::user()->id,
                            'addtime'   => now(),
                            'status'    => $request->status
                        ]);
                        $lastint++;
                        $lastcode = str_pad($lastint, 2, '0', STR_PAD_LEFT);
                        $mcode = $temp[count($temp) - 3] . $temp[count($temp) - 2] . $lastcode;
                        array_push($cnt, $menu);
                    }
                }
                if (count($cnt) == count($codes))
                {
                    return $this->success();
                } else
                {
                    return [
                        'code'=>0,
                        'msg'=>'功能简码已存在'
                    ];
                }

            } else
            {
                $menu = Menu::create([
                    'pid'       => $request->pid,
                    'name'      => $request->name,
                    'menucode'  => $request->menucode,
                    'menutype'  => $request->menutype,
                    'icon'      => $request->icon,
                    'path'      => $request->path,
                    'viewpath'  => $request->viewpath,
                    'adduserid' => Auth::user()->id,
                    'addtime'   => now(),
                    'status'    => $request->status
                ]);
                if ($menu->id > 0)
                {
                    return $this->success();
                } else
                {
                    return $this->error();
                }
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
            $menu = Menu::find($request->id);
            if (!is_null($menu))
            {
                $isok = $menu->update([
                    'status'   => $request->status,
                    'pid'      => $request->pid,
                    'name'     => $request->name,
                    'menutype' => $request->menutype,
                    'icon'     => $request->icon,
                    'path'     => $request->path,
                    'viewpath' => $request->viewpath,
                ]);
                if ($isok)
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
                    'msg'  => '参数错误',
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
            DB::transaction(function () use ($request)
            {
                $menu = Menu::find($request->id);
                $menu->delete();
                $menu->roles()->detach();
            });
            return $this->success();
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function menuroles(Request $request)
    {
        try
        {
            $menu = Menu::find($request->id);
            $menu->roles()->sync($request->roleids);
            return $this->success();
        } catch (Exception $exception)
        {

        }
    }

    public function getusers(Request $request)
    {
        try
        {
            $ids = DB::table('rolemenu')->where('rolemenu.menuid', $request->id)
                ->join('roleuser', 'roleuser.roleid', '=', 'rolemenu.roleid')
                ->join('user', 'user.id', '=', 'roleuser.userid')->select('user.id');

            return User::whereIn('id', $ids)->get();
        } catch (Exception $exception)
        {

        }
    }

    public function disable(Request $request)
    {
        try
        {
            $ok = Menu::whereIn('id', $request->ids)->where('status', 1)->update(['status' => 0]);
            if ($ok)
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

    public function enable(Request $request)
    {
        try
        {
            $ok = Menu::whereIn('id', $request->ids)->where('status', 0)->update(['status' => 1]);
            if ($ok)
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

    public function pagefuns(Request $request)
    {
        try
        {
            $query = Menu::where('status', 1)->where('menutype', '03')->where('pid', $request->id);
            $search = FunCode::joinSub($query, 'ta', 'funcode.code', '=', 'ta.name')->select([
                    'funcode.code',
                    'funcode.name',
                    'funcode.id'
                ]);
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $search->get()
            ];
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    private function checkcodeexsit($code)
    {
        $is_exsit = Menu::where('menucode', $code)->count();
        if ($is_exsit == 0)
        {
            return $code;
        } else
        {
            $last = (int)substr($code, -2);
            $last = $last + 1;
            $newcode = substr($code, 0, strlen($code) - 2) . str_pad($last, 2, '0', STR_PAD_LEFT);
            return $this->checkcodeexsit($newcode);
        }
    }

    public function maxmenucode(Request $request)
    {
        try
        {
            $code = '';
            $pid = $request->pid;
            $id = $request->id;
            if (!is_null($pid))
            {
                $cnt = Menu::where('pid', $pid)->count() + 1;
                $code = str_pad($cnt, 2, '0', STR_PAD_LEFT);
            }
            if (!is_null($id))
            {
                $menu = Menu::find($id);
                $cnt = Menu::where('pid', $id)->count() + 1;
                $code = $menu->menucode . str_pad($cnt, 2, '0', STR_PAD_LEFT);
            }
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $this->checkcodeexsit($code)
            ];
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function all_menu_tree(Request $request)
    {
        try
        {
            $id = $request->id??0;
            $json = $this->get_menu_all_tree($id);
            return [
                'code'=>1,
                'msg'=>'ok',
                'result'=>$json
            ];
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

}
