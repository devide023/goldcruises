<?php

namespace App\Observers;

use App\Code\BusProcess;
use App\Models\ProcessOrganize;
use App\Models\Repair;
use Illuminate\Support\Facades\DB;

class RepairObserver
{
    use BusProcess;

    /**
     * Handle the repair "created" event.
     *
     * @param \App\Models\Repair $repair
     * @return void
     */
    public function created(Repair $repair)
    {
        //
        $orgid = DB::table('userorg')->where('userid', $repair->adduserid)->value('departmentid');
        if (!is_null($orgid))
        {
            $processid = ProcessOrganize::where('orgid',$orgid)->value('processid');
            if(!is_null($processid)){
                $this->submit_process($processid, $repair->id);
            }
            else{
                return [
                    'code'=>0,
                    'msg'=>'未找到流程,流程未提交'
                ];
            }
        }
        else{
            return [
                'code'=>0,
                'msg'=>'未设置用户部门信息,流程未提交'
            ];
        }
    }

    /**
     * Handle the repair "updated" event.
     *
     * @param \App\Models\Repair $repair
     * @return void
     */
    public function updated(Repair $repair)
    {
        //

    }

    /**
     * Handle the repair "deleted" event.
     *
     * @param \App\Models\Repair $repair
     * @return void
     */
    public function deleted(Repair $repair)
    {
        //
    }

    /**
     * Handle the repair "restored" event.
     *
     * @param \App\Models\Repair $repair
     * @return void
     */
    public function restored(Repair $repair)
    {
        //
    }

    /**
     * Handle the repair "force deleted" event.
     *
     * @param \App\Models\Repair $repair
     * @return void
     */
    public function forceDeleted(Repair $repair)
    {
        //
    }
}
