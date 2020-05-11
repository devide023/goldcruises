<?php

namespace App\Http\Controllers\Api\Repair;

use App\Http\Controllers\Controller;
use App\Models\Repair;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepairController extends Controller
{
    //
    public function list(Request $request)
    {
        try
        {
            $pagesize = $request->pagesize ?? 15;
            $query = Repair::query();
            $query->when(!is_null($request->repairno), function (Builder $q) use ($request)
            {
                return $q->where('repairno','like','%'. $request->repairno.'%');
            });
            $query->when(!is_null($request->status),function (Builder $q) use ($request){
               return $q->where('status',$request->stauts);
            });
            $query->when(!is_null($request->type),function (Builder $q) use ($request){
                return $q->where('type',$request->type);
            });
            $query->when(!is_null($request->adduser),function (Builder $q) use ($request){
                return $q->whereHas('addusername',function (Builder $s) use ($request){
                   return $s->where('name','like','%'.$request->adduser.'%');
                });
            });
            $query->when(!is_null($request->sendperson),function (Builder $q) use ($request){
                return $q->whereHas('sendusername',function (Builder $s) use ($request){
                    return $s->where('name','like','%'.$request->sendperson.'%');
                });
            });
            $query->when(!is_null($request->enduser),function (Builder $q) use ($request){
                return $q->whereHas('endusername',function (Builder $s) use ($request){
                    return $s->where('name','like','%'.$request->enduser.'%');
                });
            });
            $query->when(!is_null($request->dealperson),function (Builder $q) use ($request){
                return $q->whereHas('dealusername',function (Builder $s) use ($request){
                    return $s->where('name','like','%'.$request->dealperson.'%');
                });
            });
            $query->when(!is_null($request->title),function (Builder $q) use ($request){
                return $q->where('title','like','%'.$request->title.'%');
            });
            $query->when(!is_null($request->note),function (Builder $q) use ($request){
                return $q->where('note','like','%'.$request->note.'%');
            });
            $query->when(!is_null($request->substance),function (Builder $q) use ($request){
                return $q->where('content','like','%'.$request->substance.'%');
            });
            $query->when(!is_null($request->senddate),function (Builder $q) use ($request){
                $date = $request->senddate;
                $date[0] = $date[0].' 0:0:0';
                $date[1] = $date[1].' 23:59:59';
                return $q->whereBetween('sendtime',$date);
            });
            $query->when(!is_null($request->adddate),function (Builder $q) use ($request){
                $date = $request->adddate;
                $date[0] = $date[0].' 0:0:0';
                $date[1] = $date[1].' 23:59:59';
                return $q->whereBetween('addtime',$date);
            });
            $query->when(!is_null($request->enddate),function (Builder $q) use ($request){
                $date = $request->enddate;
                $date[0] = $date[0].' 0:0:0';
                $date[1] = $date[1].' 23:59:59';
                return $q->whereBetween('endtime',$date);
            });
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $query->paginate($pagesize)
            ];
        } catch (Exception $exception)
        {
            return [
                'code'=>0,
                'msg'=>$exception->getMessage()
            ];
        }

    }

    public function add(Request $request)
    {
        try
        {
           $has = Repair::where('repairno',$request->repairno)->count();
           if($has>0)
           {
               return [
                   'code'=>0,
                   'msg'=>'报修单号已经存在',
                   'result'=>$this->getrepairno()
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
                'note'       => $request->note
            ]);
            if ($repair->id > 0)
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
}
