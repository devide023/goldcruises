<?php

namespace App\Http\Controllers\Api\Repair;

use App\Code\AuditIds;
use App\Code\BusProcess;
use App\Code\DataPermission;
use App\Http\Controllers\Controller;
use App\Models\ContractFile;
use App\Models\ProcessInfo;
use App\Models\Repair;
use App\Models\RepairDetail;
use App\Models\RepairDetailImg;
use App\Models\RepairImage;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class RepairController extends Controller
{
    use DataPermission;
    use AuditIds;
    use BusProcess;

    //
    /*
     * 总数据列表
     */
    public function list(Request $request)
    {
        try
        {
            $pagesize = $request->pagesize ?? 15;
            $query = Repair::query();
            $query->when(!is_null($request->repairno), function (Builder $q) use ($request)
            {
                return $q->where('repairno', 'like', '%' . $request->repairno . '%');
            });
            $query->when(!is_null($request->status), function (Builder $q) use ($request)
            {
                return $q->where('status', $request->stauts);
            });
            $query->when(!is_null($request->type), function (Builder $q) use ($request)
            {
                return $q->where('type', $request->type);
            });
            $query->when(!is_null($request->adduserid), function (Builder $q) use ($request)
            {
                return $q->where('adduserid', $request->adduserid);
            });
            $query->when(!is_null($request->senduserid), function (Builder $q) use ($request)
            {
                return $q->where('senduserid', $request->senduserid);
            });
            $query->when(!is_null($request->dealuserid), function (Builder $q) use ($request)
            {
                return $q->where('dealuserid', $request->dealuserid);
            });
            $query->when(!is_null($request->enduserid), function (Builder $q) use ($request)
            {
                return $q->where('enduserid', $request->enduserid);
            });
            $query->when(!is_null($request->adduser), function (Builder $q) use ($request)
            {
                return $q->whereHas('addusername', function (Builder $s) use ($request)
                {
                    return $s->where('name', 'like', '%' . $request->adduser . '%');
                });
            });
            $query->when(!is_null($request->sendperson), function (Builder $q) use ($request)
            {
                return $q->whereHas('sendusername', function (Builder $s) use ($request)
                {
                    return $s->where('name', 'like', '%' . $request->sendperson . '%');
                });
            });
            $query->when(!is_null($request->enduser), function (Builder $q) use ($request)
            {
                return $q->whereHas('endusername', function (Builder $s) use ($request)
                {
                    return $s->where('name', 'like', '%' . $request->enduser . '%');
                });
            });
            $query->when(!is_null($request->dealperson), function (Builder $q) use ($request)
            {
                return $q->whereHas('dealusername', function (Builder $s) use ($request)
                {
                    return $s->where('name', 'like', '%' . $request->dealperson . '%');
                });
            });
            $query->when(!is_null($request->title), function (Builder $q) use ($request)
            {
                return $q->where('title', 'like', '%' . $request->title . '%');
            });
            $query->when(!is_null($request->note), function (Builder $q) use ($request)
            {
                return $q->where('note', 'like', '%' . $request->note . '%');
            });
            $query->when(!is_null($request->substance), function (Builder $q) use ($request)
            {
                return $q->where('content', 'like', '%' . $request->substance . '%');
            });
            $query->when(!is_null($request->senddate), function (Builder $q) use ($request)
            {
                $date = $request->senddate;
                $date[0] = $date[0] . ' 0:0:0';
                $date[1] = $date[1] . ' 23:59:59';
                return $q->whereBetween('sendtime', $date);
            });
            $query->when(!is_null($request->adddate), function (Builder $q) use ($request)
            {
                $date = $request->adddate;
                $date[0] = $date[0] . ' 0:0:0';
                $date[1] = $date[1] . ' 23:59:59';
                return $q->whereBetween('addtime', $date);
            });
            $query->when(!is_null($request->enddate), function (Builder $q) use ($request)
            {
                $date = $request->enddate;
                $date[0] = $date[0] . ' 0:0:0';
                $date[1] = $date[1] . ' 23:59:59';
                return $q->whereBetween('endtime', $date);
            });
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $query->orderBy('id', 'desc')->paginate($pagesize)
            ];
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }

    }
    /*
     * 报修人及维修人所看列表
     */
    public function myrepairlist(Request $request)
    {
        try
        {
            $pagesize = $request->pagesize ?? 15;
            $orgids = $this->current_user_datapermission();

            $query = Repair::whereIn('orgid', $orgids);
            $query = $query->where(function (Builder $q) use ($request)
            {
                $uid = Auth::id();
                return $q->orWhere('adduserid', $uid)->orWhere('dealuserid', $uid);
            });
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $query->orderBy('id', 'desc')->paginate($pagesize)
            ];
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }

    }
    /*
     * 用户数据权限范围的列表
     */
    public function orgrepairlist(Request $request)
    {
        try
        {
            $pagesize = $request->pagesize ?? 15;
            $orgids = $this->current_user_datapermission();
            $query = Repair::whereIn('orgid', $orgids);
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $query->orderBy('id', 'desc')->paginate($pagesize)
            ];

        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }
    /*
     * 任务列表
     */
    public function mytasklist(Request $request)
    {
        try
        {
            $pagesize = $request->pagesize ?? 15;
            $query = Repair::query();
            $query = $query->whereIn('id', $this->current_audit_ids(1));
            $query = $query->whereIn('orgid', $this->current_user_datapermission());
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $query->orderBy('id', 'desc')->paginate($pagesize)
            ];
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }

    }

    public function add(Request $request)
    {
        try
        {
            $has = Repair::where('repairno', $request->repairno)->count();
            $orgid = DB::table('userorg')->where('userid', Auth::id())->value('departmentid') ?? 0;
            if ($has > 0)
            {
                return [
                    'code' => 0,
                    'msg'  => '报修单号重复'
                ];
            }
            $repair = Repair::create([
                'status'     => $request->status,
                'type'       => $request->type,
                'repairno'   => $request->repairno,
                'title'      => $request->title,
                'content'    => $request->substance,
                'adduserid'  => Auth::id(),
                'adduser'    => Auth::user()->name,
                'addusertel' => $request->addusertel,
                'addtime'    => now(),
                'note'       => $request->note,
                'orgid'      => $orgid
            ]);
            if ($repair->id > 0)
            {
                $imgnames = $request->images;
                if (!is_null($imgnames))
                {
                    DB::table('repairimage')->whereIn('filename', $imgnames)->update([
                        'repairid' => $repair->id
                    ]);
                }
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

    /*
     * 保存维修详情
     */
    public function savedealinfo(Request $request)
    {
        try
        {
            $repairid = $request->repairid;
            if (!is_null($repairid))
            {
                DB::beginTransaction();
                Repair::where('id', $repairid)->update([
                    'status' => '02'
                ]);
                $detail = RepairDetail::create([
                    'repairid'    => $repairid,
                    'content'     => $request->dealcontent,
                    'dealuser'    => $request->dealuser,
                    'dealusertel' => $request->dealusertel,
                    'note'        => $request->note,
                    'adduserid'   => Auth::id(),
                    'dealtime'    => now()
                ]);
                if ($detail->id > 0)
                {
                    Repair::where('id', $repairid)->update([
                        'dealperson'    => $request->dealuser,
                        'dealpersontel' => $request->dealusertel
                    ]);
                    $images = $request->images;
                    if (!is_null($images))
                    {
                        $cnt = DB::table('repairdetailimg')->whereIn('filename', $images)->update([
                            'detailid' => $detail->id
                        ]);
                    }
                    DB::commit();
                    return $this->success();
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

    /*
     * 删除未更新repairid的图片
     */
    public function removeimgs(Request $request)
    {
        try
        {
            $images = $request->images;
            if (!is_null($images))
            {
                foreach ($images as $image)
                {
                    $ok = Storage::disk('local')->delete('/repair/' . $image);
                    if ($ok)
                    {
                        $id = DB::table('repairimage')->where('filename', $image)->where('repairid', '=', null)
                            ->delete();
                    }
                }
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

    public function remove_detailimgs(Request $request)
    {
        try
        {
            $images = $request->images;
            if (!is_null($images))
            {
                foreach ($images as $image)
                {
                    $ok = Storage::disk('local')->delete('/repair/' . $image);
                    if ($ok)
                    {
                        $id = DB::table('repairdetailimg')->where('filename', $image)->delete();
                    }
                }
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

    public function edit(Request $request)
    {
        try
        {
            $repair = Repair::where('id', $request->id)->where('status', '=', '01');
            $ok = $repair->update([
                'type'       => $request->type,
                'repairno'   => $request->repairno,
                'title'      => $request->title,
                'content'    => $request->substance,
                'addusertel' => $request->addusertel,
                'note'       => $request->note
            ]);
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

    public function find(Request $request)
    {
        try
        {
            $repair = Repair::find($request->id);
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $repair
            ];

        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function getrepairno(Request $request)
    {
        try
        {
            $cnt = Repair::count('id') + 1;
            $code = 'R' . str_pad($cnt, 6, '0', STR_PAD_LEFT);
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $code
            ];
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }

    }

    public function uploadrepairimg(Request $request)
    {
        try
        {
            $file = $request->file('file');
            $newfilename = Uuid::uuid1()->getHex() . '.' . $file->clientExtension();
            $tmpFile = $file->getRealPath();
            $issaved = Storage::disk('local')->put('/repair/' . $newfilename, file_get_contents($tmpFile));
            if ($issaved)
            {
                $repairimg = RepairImage::create([
                    'filename'     => $newfilename,
                    'filetype'     => $file->clientExtension(),
                    'originalname' => $file->getClientOriginalName(),
                    'adduserid'    => Auth::id(),
                    'addtime'      => now()
                ]);
                if ($repairimg->id > 0)
                {
                    return [
                        'code'   => 1,
                        'msg'    => 'ok',
                        'result' => $newfilename
                    ];
                } else
                {
                    return $this->error();
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

    public function uploadrepairdetailimg(Request $request)
    {
        try
        {
            $file = $request->file('file');
            $newfilename = Uuid::uuid1()->getHex() . '.' . $file->clientExtension();
            $tmpFile = $file->getRealPath();
            $issaved = Storage::disk('local')->put('/repair/' . $newfilename, file_get_contents($tmpFile));
            if ($issaved)
            {
                $repairdetailimg = RepairDetailImg::create([
                    'filename'     => $newfilename,
                    'filetype'     => $file->clientExtension(),
                    'originalname' => $file->getClientOriginalName(),
                    'adduserid'    => Auth::id(),
                    'addtime'      => now()
                ]);
                if ($repairdetailimg->id > 0)
                {
                    return [
                        'code'   => 1,
                        'msg'    => 'ok',
                        'result' => $newfilename
                    ];
                } else
                {
                    return $this->error();
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

    public function getrepair_infolist(Request $request)
    {
        try
        {
            $repairid = $request->repairid;
            $pagesize = $request->pagesize ?? 15;
            if (!is_null($repairid))
            {
                $details = RepairDetail::where('repairid', $repairid);
                return [
                    'code'   => 1,
                    'msg'    => 'ok',
                    'result' => $details->paginate($pagesize)
                ];
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

    public function auditor(Request $request)
    {
        try
        {
            $ta = DB::table('userorg')->where('userid', Auth::id());
            $deptids = DB::table('userorg')->joinSub($ta, 'ta', 'userorg.userid', '=', 'ta.userid')
                ->select(['userorg.departmentid']);
            $tb = DB::table('userorg')->whereIn('departmentid', $deptids)->select([
                'userorg.userid'
            ]);
            $users = DB::table('user')->joinSub($tb, 'tb', 'user.id', '=', 'tb.userid')->select([
                'user.id',
                'user.name',
                'user.usercode'
            ])->get();
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $users
            ];
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }

    }

    /*
     * 报修图片列表
     */
    public function repairimgs(Request $request)
    {
        try
        {
            $repairid = $request->id ?? 0;
            $imgs = RepairImage::where('repairid', $repairid)->get(['filename']);
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $imgs
            ];
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }

    }

    /*
     * 下一步
     */
    public function repair_next(Request $request)
    {
        try
        {
            $billid = $request->billid ?? 0;
            $ret = $this->next_step(1, $billid);
            return $ret;
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

    /*
     * 拒绝
     */
    public function disgree_bill(Request $request)
    {
        try
        {
            $ret = $this->disgree_process(1, $request->billid ?? 0);
            if ($ret)
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

    /*
     * 获取当前登录人组织节点下人员
     */
    public function deal_userlist(Request $request)
    {
        try
        {
            $orgid = DB::table('userorg')->where('userorg.userid', Auth::id())->select(['userorg.departmentid'])
                ->pluck('departmentid');
            $users = DB::table('userorg')->join('user', 'userorg.userid', '=', 'user.id')
                ->whereIn('userorg.departmentid', $orgid)->select([
                    'user.id',
                    'user.name'
                ])->get();
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $users
            ];
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

    /*
     * 派单
     */
    public function sendbill(Request $request)
    {
        try
        {
            $billid = $request->billid ?? 0;
            $dealuserid = $request->dealuserid ?? 0;
            if ($billid > 0 && $dealuserid > 0)
            {
                $dealuser = User::find($dealuserid);
                $ok = Repair::find($billid)->update([
                    'dealuserid'    => $dealuserid,
                    'dealperson'    => $dealuser->name,
                    'dealpersontel' => $dealuser->tel,
                    'senduserid'    => Auth::id(),
                    'sendperson'    => Auth::user()->name,
                    'sendtime'      => now(),
                    'sendnote'      => $request->note
                ]);
                if ($ok)
                {
                    $this->next_step(1, $billid);
                    return $this->success();
                } else
                {
                    return $this->error();
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

    /*
     * 获取表单当前步骤
     */
    public function billstepno(Request $request)
    {
        try
        {
            $billid = $request->billid ?? 0;
            $ret = $this->current_step(1, $billid);
            return $ret;
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

    /*
     * 验收
     */
    public function checkbill(Request $request)
    {
        try
        {
            $billid = $request->billid ?? 0;
            $ok = Repair::find($billid)->update([
                'enduserid' => Auth::id(),
                'enduser'   => Auth::user()->name,
                'endtime'   => now(),
                'status'    => '03'
            ]);
            if ($ok)
            {
                $cnt = ProcessInfo::where('billid', $billid)->where('processid', '=', 1)->where('isover', '=', 0)
                    ->update([
                        'isover' => 1
                    ]);
                if ($cnt > 0)
                {
                    return $this->success();
                } else
                {
                    return $this->error();
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
}
