<?php


namespace App\Code;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait AuditIds
{
    public function current_audit_ids($processid){
        $tb = DB::table('processstepuser')->where('userid', Auth::id());
        $t1 = DB::table('processinfo')->where('processid', $processid)->where('isover', '=', 0);
        $billids = DB::table('processstep')->joinSub($tb, 'tb', 'processstep.id', '=', 'tb.processstepid')->select([
            'processstep.processid',
            'processstep.stepno',
            'processstep.stepname'
        ])->joinSub($t1, 't1', 'processstep.stepno', '=', 't1.stepno')->select(['t1.billid'])->pluck('billid');
        if (!is_null($billids))
        {
            return $billids;
        } else
        {
            return [];
        }
    }

    public function user_audit_ids($processid,$userid)
    {
        try
        {
            $tb = DB::table('processstepuser')->where('userid', $userid);
            $t1 = DB::table('processinfo')->where('processid', $processid)->where('isover', '=', 0);
            $billids = DB::table('processstep')->joinSub($tb, 'tb', 'processstep.id', '=', 'tb.processstepid')->select([
                'processstep.processid',
                'processstep.stepno',
                'processstep.stepname'
            ])->joinSub($t1, 't1', 'processstep.stepno', '=', 't1.stepno')->select(['t1.billid'])->pluck('billid');
            if (!is_null($billids))
            {
                return $billids;
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
}
