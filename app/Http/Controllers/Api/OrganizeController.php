<?php

namespace App\Http\Controllers\Api;

use App\Code\Utils;
use App\Http\Controllers\Controller;
use App\Models\Organize;
use App\Models\OrganizeType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrganizeController extends Controller
{
    use Utils;

    //
    public function list(Request $request)
    {
        try
        {
            $query = Organize::query();
            $query->when($request->id, function (Builder $q) use ($request)
            {
                return $q->where('id', $request->id);
            });
            $query->when(!is_null($request->pid), function (Builder $q) use ($request)
            {
                return $q->where('pid', '=', $request->pid);
            });
            $query->when($request->name, function (Builder $q) use ($request)
            {
                return $q->where('name', 'like', '%' . $request->name . '%');
            });
            $query->when($request->orgcode, function (Builder $q) use ($request)
            {
                return $q->where('orgcode', $request->orgcode);
            });
            $query->when($request->status, function (Builder $q) use ($request)
            {
                return $q->where('status', $request->status);
            });
            $query->when($request->orgtype, function (Builder $q) use ($request)
            {
                return $q->where('orgtype', $request->orgtype);
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
                'result' => $query->paginate()
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
            $org = Organize::create([
                'status'    => $request->status,
                'orgtype'   => $request->orgtype,
                'name'      => $request->name,
                'leader'    => $request->leader,
                'pid'       => $request->pid,
                'orgcode'   => $request->orgcode,
                'logo'      => $request->logo,
                'addtime'   => now(),
                'adduserid' => Auth::user()->id
            ]);
            if ($org->id > 0)
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

    public function edit(Request $request)
    {
        try
        {
            $org = Organize::find($request->id);
            if (!is_null($org))
            {
                $isok = $org->update([
                    'status'  => $request->status,
                    'orgtype' => $request->orgtype,
                    'name'    => $request->name,
                    'leader'  => $request->leader,
                    'pid'     => $request->pid,
                    'orgcode' => $request->orgcode,
                    'logo'    => $request->logo,
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
                    'msg'  => '参数错误'
                ];
            }

        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    private function createorgcode($pid)
    {
        $cnt = Organize::where('pid', $pid)->count();
        $code = Organize::where('id', $pid)->value('orgcode');
        $newcode = $code . str_pad($cnt + 1, 2, '0', STR_PAD_LEFT);
        return $this->checkorgcode($newcode);
    }

    private function checkorgcode($code)
    {
        $cnt = Organize::where('orgcode', $code)->count();
        if ($cnt > 0)
        {
            $newcode = substr($code, 0, count($code) - 3) . str_pad(((int)substr($code, -2)) + 1, 2, '0', STR_PAD_LEFT);
            $this->checkorgcode($newcode);
        } else
        {
            return $code;
        }
    }

    public function create_node(Request $request)
    {
        try
        {
            $newnode = Organize::create([
                'status'    => 1,
                'pid'       => $request->id,
                'name'      => '新节点',
                'orgcode'   => Str::random(4),
                'orgtype'   => $request->id == 0 ? '01' : '03',
                'adduserid' => Auth::id(),
                'addtime'   => now()
            ]);
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $newnode
            ];

        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function edited_node(Request $request)
    {
        try
        {
            $node = $request->data;
            $cnt = Organize::where('id',$node['id'])->update([
                'name'=>$node['label'],
            ]);
            if($cnt>0){
                return $this->success();
            }else{
                return $this->error();
            }
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function remove_node(Request $request)
    {
        try
        {
            $haschild = Organize::where('pid', $request->id)->count();
            if ($haschild)
            {
                return [
                    'code' => 0,
                    'msg'  => '该节点不是叶子节点不能删除',

                ];
            } else
            {
                $org = Organize::find($request->id);
                if ($org->delete())
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

    public function saveallorg(Request $request)
    {
        try
        {
            $orgs = $request->orgtree;
            DB::beginTransaction();
            foreach ($orgs as $key => $org)
            {
                $isadd = key_exists('id', $org);
                $haschild = key_exists('children', $org);
                if (!$isadd)
                {
                    Organize::create([
                        'pid'       => $org['parentid'],
                        'name'      => $org['label'],
                        'adduserid' => Auth::id(),
                        'addtime'   => now(),
                        'status'    => 1
                    ]);
                } else
                {
                    Organize::where('id', $org["id"])->update([
                        'pid'  => $org['parentid'],
                        'name' => $org['label'],
                    ]);
                }
                if ($haschild)
                {
                    $this->savesuborg($org['children']);
                }
            }
            DB::commit();
            return [
                'code'   => 1,
                'msg'    => '数据保存成功',
                'result' => $this->alltree($request)
            ];
        } catch (Exception $exception)
        {
            DB::rollBack();
            throw  $exception;
        }
    }

    private function savesuborg($children)
    {
        foreach ($children as $key => $org)
        {
            $isadd = key_exists('id', $org);
            $haschild = key_exists('children', $org);
            if (!$isadd)
            {
                Organize::create([
                    'pid'       => $org['parentid'],
                    'name'      => $org['label'],
                    'adduserid' => Auth::id(),
                    'addtime'   => now(),
                    'status'    => 1
                ]);
            } else
            {
                Organize::where('id', $org['id'])->update([
                    'pid'  => $org['parentid'],
                    'name' => $org['label'],
                ]);
            }
            if ($haschild)
            {
                $this->savesuborg($org['children']);
            }
        }
    }

    public function alltree(Request $request)
    {
        try
        {
            $pid = $request->id ?? 0;
            $nodes = $this->get_org_all_tree($pid);
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $nodes
            ];
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function getorgusers(Request $request)
    {
        try
        {
            $org = Organize::find($request->id);
            $users = $org->users()->get();
            return [
                'code'=>1,
                'msg'=>'ok',
                'result'=>$users
            ];
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    /**
     * @param Request $request
     * 获取当前节点下的子节点
     */
    public function curentnodes(Request $request)
    {
        try
        {
            $nodes = Organize::where('pid', $request->id)->select([
                'id',
                'pid',
                'name as label',
                'orgcode',
                'orgtype'
            ])->get();
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $nodes
            ];
        } catch (Exception $exception)
        {
            throw  $exception;
        }
    }

    public function find(Request $request)
    {
        try
        {
            $org = Organize::find($request->id);
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $org
            ];
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
                $org = Organize::find($request->id);
                $org->users()->detach();
                $org->delete();
            });
            return $this->success();
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }
}
