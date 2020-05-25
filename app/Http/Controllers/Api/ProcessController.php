<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusProcess;
use App\Models\ProcessInfo;
use App\Models\Processstep;
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
