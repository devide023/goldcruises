<?php


namespace App\Code;


use App\Models\Organize;
use App\Models\ProcessInfo;
use App\Models\ProcessOrganize;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait BusProcess
{
    use AuditIds;
    use WeChat;
    public function current_step($process_id, $bill_id)
    {
        try
        {
            if ($bill_id > 0 && $process_id > 0)
            {
                $totalsteps = \App\Models\BusProcess::find($process_id)->steps()->count();
                $info = ProcessInfo::where('billid', $bill_id)->where('processid', $process_id)->first();
                if (!is_null($info))
                {
                    return [
                        'code'     => 1,
                        'msg'      => 'ok',
                        'stepinfo' => [
                            'totalstep'   => $totalsteps,
                            'currentstep' => $info->stepno,
                            'isover'      => $info->isover
                        ]
                    ];
                } else
                {
                    return [
                        'code'     => 0,
                        'msg'      => '未查询到流程信息',
                        'stepinfo' => [
                            'totalstep'   => $totalsteps,
                            'currentstep' => null,
                            'isover'      => null
                        ]
                    ];
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
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }

    }

    public function next_step($process_id, $bill_id)
    {
        try
        {
            $bill_id = (integer)$bill_id;
            $totalstep = \App\Models\BusProcess::find($process_id)->steps()->count();
            $stepno = ProcessInfo::where('processid', $process_id)->where('billid', $bill_id)->value('stepno');
            if (is_null($stepno))
            {
                return [
                    'code' => 0,
                    'msg'  => '流程未启动,请提交流程'
                ];
            }
            $ids = $this->current_audit_ids($process_id)->toArray();
            $has = in_array($bill_id, $ids, true);
            if (!$has)
            {
                return [
                    'code' => 0,
                    'msg'  => '流程已提交'
                ];
            }
            if ($stepno <= $totalstep)
            {
                if ($stepno == $totalstep)
                {
                    ProcessInfo::where('processid', $process_id)->where('billid', $bill_id)->update(['isover' => 1]);
                } else
                {
                    $cnt = ProcessInfo::where('processid', $process_id)->where('billid', $bill_id)->increment('stepno');
                    if ($cnt > 0)
                    {
                        $this->sendmsg($bill_id);
                        return [
                            'code' => 1,
                            'msg'  => '数据提交成功'
                        ];
                    } else
                    {
                        return [
                            'code' => 0,
                            'msg'  => '数据提交失败'
                        ];
                    }
                }
            } else
            {
                return [
                    'code' => 0,
                    'msg'  => '流程已完成'
                ];
            }


        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }

    }

    public function submit_process($process_id, $bill_id)
    {
        try
        {
            ProcessInfo::create([
                'processid' => $process_id,
                'billid'    => $bill_id,
                'stepno'    => 1,
                'adduserid' => Auth::id(),
                'addtime'   => now(),
                'flow'      => 1,
                'isover'    => 0
            ]);
            $this->sendmsg($bill_id);
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }

    }

    public function current_step_users($process_id, $bill_id)
    {
        try
        {
            $q = DB::table('processinfo')->join('repair', 'processinfo.billid', '=', 'repair.id')
                ->where('processinfo.processid', $process_id)->where('processinfo.billid', $bill_id)
                ->where('processinfo.isover', '=', 0)->select([
                    'processinfo.stepno',
                    'repair.orgid'
                ]);
            $stepno = $q->value('stepno');
            if (!is_null($stepno))
            {
                $query = DB::table('processstep')
                    ->join('processstepuser', 'processstep.id', '=', 'processstepuser.processstepid')
                    ->where('processstep.stepno', $stepno)->select([
                        'processstepuser.userid'
                    ])->distinct();
                return $query->pluck('userid');
            } else
            {
                return [];
            }
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

    public function disgree_process($process_id, $bill_id)
    {
        $ok = ProcessInfo::where('processid', $process_id)->where('billid', $bill_id)->where('isover', '=', '0')
            ->delete();
        return $ok;
    }

    /*
     * 查找最近组织节点流程(自下而上)
     */
    public function search_orgprocess($orgid)
    {
        $orgobj = Organize::find($orgid);
        $proorg = ProcessOrganize::where('orgid', $orgid);
        if ($proorg->count() > 0)
        {
            return $proorg->value('processid');
        } else
        {
            if ($orgobj->pid > 0)
            {
                return $this->search_orgprocess($orgobj->pid);
            } else
            {
                return 0;
            }
        }
    }

}
