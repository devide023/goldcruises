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
    public function addprocess(Request $request)
    {
        try {
            DB::beginTransaction();
            $process = \App\Models\BusProcess::create([
                'name'      => $request->name,
                'status'    => $request->status,
                'adduserid' => Auth::id(),
                'addtime'   => now(),
                'orgid'     => $request->orgid
            ]);
            $steps = $request->steps;
            $data = [];
            foreach ($steps as $step) {
                $stepobj = Processstep::create([
                    'stepno'   => $step['stepno'],
                    'stepname' => $step['stepname'],
                    'addtime'  => now()
                ]);
                $userids = $step['userid'];
                foreach ($userids as $userid) {
                    $stepobj->stepusers()
                        ->create([
                            'userid' => $userid
                        ]);
                }
                array_push($data, $stepobj);
            }
            $process->steps()
                ->saveMany($data);
            DB::commit();
            if ($process->id > 0) {
                return $this->success();
            }
            else {
                return $this->error();
            }
        }
        catch (Exception $exception) {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

    public function get_currentuser_process(Request $request)
    {
        try {
            $process = BusProcess::whereHas('steps.stepusers', function (Builder $q) use ($request)
            {
                return $q->where('userid', Auth::id());
            })->get();
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $process
            ];
        }
        catch (Exception $exception) {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }

    }

    public function current_step(Request $request)
    {
        try {
            $bill_id = $request->billid ?? 0;
            $process_id = $request->processid ?? 0;
            if ($bill_id > 0 && $process_id > 0) {
                $totalsteps = BusProcess::find($process_id)
                    ->steps()
                    ->count();
                $maxstep = ProcessInfo::where('billid', $bill_id)
                    ->where('processid', $process_id)
                    ->max('stepno');
                if (is_null($maxstep)) {
                    $maxstep = 0;
                }
                return [
                    'code'   => 1,
                    'msg'    => 'ok',
                    'result' => [
                        'totalstep' => $totalsteps,
                        'current'   => $maxstep
                    ]
                ];
            }
            else {
                return [
                    'code' => 0,
                    'msg'  => '参数错误'
                ];
            }
        }
        catch (Exception $exception) {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }

    }

    public function next_step(Request $request)
    {
        try {
            $bill_id = $request->billid ?? 0;
            $process_id = $request->processid ?? 0;
            $totalstep = BusProcess::find($process_id)->steps()->count();
            $stepno = ProcessInfo::where('processid', $process_id)
                ->where('billid', $bill_id)
                ->value('stepno');
            if(is_null($stepno)){
                return [
                    'code'=>0,
                    'msg'=>'流程未启动,请提交流程'
                ];
            }
            if($stepno<$totalstep){
                $cnt = ProcessInfo::where('processid', $process_id)
                    ->where('billid', $bill_id)
                    ->increment('stepno');
                if ($cnt > 0) {
                    return $this->success();
                }
                else {
                    return $this->error();
                }
            }else{
                return [
                    'code'=>0,
                    'msg'=>'流程已完成'
                ];
            }


        }
        catch (Exception $exception) {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }

    }
}
