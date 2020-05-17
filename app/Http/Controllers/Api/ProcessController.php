<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusProcess;
use App\Models\Processstep;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProcessController extends Controller
{
    //
    public function list(Request $request)
    {
        try {
            $query = BusProcess::query();
            $pagesize = $request->pagesize ?? 15;

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
            $process = BusProcess::create([
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
            $process = BusProcess::whereHas('steps.stepusers',function (Builder $q) use ($request){
                return $q->where('userid',Auth::id());
            })->get();
            return [
                'code'=>1,
                'msg'=>'ok',
                'result'=>$process
            ];
        }
        catch (Exception $exception) {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }

    }

}
