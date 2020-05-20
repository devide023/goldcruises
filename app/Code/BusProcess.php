<?php


namespace App\Code;


use App\Models\ProcessInfo;
use App\Models\Processstep;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait BusProcess
{

    public function current_step($process_id, $bill_id)
    {
        try
        {
            if ($bill_id > 0 && $process_id > 0)
            {
                $totalsteps = \App\Models\BusProcess::find($process_id)->steps()->count();
                $maxstep = ProcessInfo::where('billid', $bill_id)->where('processid', $process_id)->value('stepno');
                if (is_null($maxstep))
                {
                    $maxstep = 0;
                }
                return [
                    'code'     => 1,
                    'msg'      => 'ok',
                    'stepinfo' => [
                        'totalstep'   => $totalsteps,
                        'currentstep' => $maxstep
                    ]
                ];
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
            $totalstep = \App\Models\BusProcess::find($process_id)->steps()->count();
            $stepno = ProcessInfo::where('processid', $process_id)->where('billid', $bill_id)->value('stepno');
            if (is_null($stepno))
            {
                return [
                    'code' => 0,
                    'msg'  => '流程未启动,请提交流程'
                ];
            }
            if ($stepno <= $totalstep)
            {
                if ($stepno == $totalstep)
                {
                    ProcessInfo::where('processid', $process_id)->where('billid', $bill_id)->update(
                        ['isover'=>1]
                    );
                } else
                {
                    $cnt = ProcessInfo::where('processid', $process_id)->where('billid', $bill_id)->increment('stepno');
                    if ($cnt > 0)
                    {
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

    public function disgree_process($process_id, $bill_id){
        $ok = ProcessInfo::where('processid',$process_id)
            ->where('billid',$bill_id)
            ->where('isover','=','0')
            ->delete();
        return $ok;
    }

}
