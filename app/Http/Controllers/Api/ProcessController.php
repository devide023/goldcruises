<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusProcess;
use App\Models\ProcessInfo;
use App\Models\ProcessOrganize;
use App\Models\Processstep;
use App\Models\Repair;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProcessController extends Controller
{
    use  \App\Code\BusProcess;
    //
    public function list(Request $request)
    {
        try {
            $query = BusProcess::query();
            $pagesize = $request->pagesize ?? 15;
            $query->when(!is_null($request->name),function (Builder $q) use ($request){
               return $q->where('name','like','%'.$request->name.'%');
            });
            return ['code'   => 1,
                    'msg'    => 'ok',
                    'result' => $query->paginate($pagesize)
            ];
        }
        catch (Exception $exception) {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }
    public function addprocess(Request $request)
    {
        try {
            DB::beginTransaction();
            $process = \App\Models\BusProcess::create([
                'name'      => $request->name,
                'status'    => $request->status,
                'adduserid' => Auth::id(),
                'addtime'   => now()
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
            $orgdata=[];
            foreach ($request->orgids as $orgid){
                array_push($orgdata,[
                   'orgid'=>$orgid,
                   'adduserid'=>Auth::id(),
                   'addtime'=>now()
                ]);
            }
            $process->processorgs()->createMany($orgdata);
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

    public function updateprocess(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $processid = $request->id??0;
            $processentity = BusProcess::find($processid);
            $ok = $processentity->update([
                'name' => $request->name
            ]);
            if($ok){
                //更新组织节点
                $processentity->processorgs()->delete();
                $orgdata=[];
                foreach ($request->orgids as $orgid){
                    array_push($orgdata,[
                        'orgid'=>$orgid,
                        'adduserid'=>Auth::id(),
                        'addtime'=>now()
                    ]);
                }
                $processentity->processorgs()->createMany($orgdata);
                //更新步骤
                $processentity->steps()->delete();
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
                $processentity->steps()
                    ->saveMany($data);
                DB::commit();
                return $this->success();

            }

        } catch (Exception $exception)
        {
            DB::rollBack();
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }
    public function delprocess(Request $request)
    {
        try
        {
            $processid = $request->id??0;
            if($processid>0){
                $hav = ProcessInfo::where('processid',$processid)
                    ->where('isover','=','0')
                    ->count();
                if($hav==0){
                    DB::beginTransaction();
                    BusProcess::find($processid)->delete();
                    Processstep::where('processid',$processid)->delete();
                    ProcessOrganize::where('processid',$processid)->delete();
                    DB::commit();
                    return $this->success();
                }else{
                    return [
                        'code'=>0,
                        'msg'=>'该流程正在执行不能删除'
                    ];
                }
            }else{
                return $this->error();
            }
        } catch (Exception $exception)
        {
            DB::rollBack();
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }
    public function stepusers(Request $request)
    {
        try
        {
            $processid = $request->processid;
            $billid = $request->billid;
            $users = $this->current_step_users($processid,$billid);
            return [
                'code'=>1,
                'msg'=>'ok',
                'result'=>$users
            ];

        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }

    }

    public function getsetplist(Request $request)
    {
        try
        {
            $processid = $request->processid;
            $steps = Processstep::where('processid',$processid)
                ->orderBy('stepno','asc')
                ->get();
            return [
                'code'=>1,
                'msg'=>'ok',
                'result'=>$steps
            ];
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }

    }

}
