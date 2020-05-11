<?php

namespace App\Http\Controllers\Api\Contract;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\ContractFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class ContractController extends Controller
{
    //
    public function list(Request $request)
    {
        try
        {
            $pagesize = $request->pagesize ?? 15;
            $query = Contract::query();
            $query->when(!is_null($request->status), function (Builder $q) use ($request)
            {
                return $q->where('status', $request->status);
            });
            $query->when(!is_null($request->type), function (Builder $q) use ($request)
            {
                return $q->where('type', $request->type);
            });
            $query->when(!is_null($request->contractno), function (Builder $q) use ($request)
            {
                return $q->where('contractno', 'like', '%' . strtolower($request->contractno) . '%');
            });
            $query->when(!is_null($request->name), function (Builder $q) use ($request)
            {
                return $q->where('name', 'like', '%' . strtolower($request->name) . '%');
            });
            $query->when(!is_null($request->contractcompany), function (Builder $q) use ($request)
            {
                return $q->where('contractcompany', 'like', '%' . strtolower($request->contractcompany) . '%');
            });
            $query->when(!is_null($request->payway), function (Builder $q) use ($request)
            {
                return $q->where('payway', $request->payway);
            });
            $query->when(!is_null($request->dutyperson), function (Builder $q) use ($request)
            {
                return $q->where('dutyperson', $request->dutyperson);
            });
            $query->when(!is_null($request->cntidentity), function (Builder $q) use ($request)
            {
                return $q->where('cntidentity', $request->cntidentity);
            });
            $query->when(!is_null($request->adduser), function (Builder $q) use ($request)
            {
                return $q->whereHas('adduser', function (Builder $s) use ($request)
                {
                    return $s->where('name', 'like', '%' . $request->adduser . '%');
                });
            });
            $query->when(!is_null($request->amount) && !is_null($request->amount1), function (Builder $q) use ($request)
            {
                return $q->whereBetween('amount', [
                    $request->amount,
                    $request->amount1
                ]);
            });
            $query->when(!is_null($request->signdate), function (Builder $q) use ($request)
            {
                return $q->whereBetween('signdate', $request->signdate);
            });
            $query->when(!is_null($request->edate), function (Builder $q) use ($request)
            {
                return $q->whereBetween('edate', $request->edate);
            });
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $query->paginate($pagesize)
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
            $contractno = $request->contractno;
            $has = Contract::where('contractno', $contractno)->count();
            if ($has == 0)
            {
                $contract = Contract::create([
                    'status'          => $request->status,
                    'contractno'      => $request->contractno,
                    'name'            => $request->name,
                    'type'            => $request->type,
                    'amount'          => $request->amount,
                    'payedamount'     => $request->payedamount,
                    'payway'          => $request->payway,
                    'cntidentity'     => $request->cntidentity,
                    'contractcompany' => $request->contractcompany,
                    'contractor'      => $request->contractor,
                    'contractortel'   => $request->contractortel,
                    'signdate'        => $request->signdate,
                    'bdate'           => $request->bdate,
                    'edate'           => $request->edate,
                    'moneyflow'       => $request->moneyflow,
                    'dutyperson'      => $request->dutyperson,
                    'dutypersontel'   => $request->dutypersontel,
                    'adduserid'       => \Auth::id(),
                    'addtime'         => now()
                ]);
                if ($contract->id > 0)
                {
                    return $this->success();
                } else
                {
                    return $this->error();
                }
            } else
            {
                return [
                    'code' => 0,
                    'msg'  => '合同编号已存在'
                ];
            }
        } catch (Exception $exception)
        {
            DB::rollBack();
            throw  $exception;
        }

    }

    public function edit(Request $request)
    {
        try
        {
            if (!is_null($request->id))
            {
                $contract = Contract::find($request->id);
                $ok = $contract->update([
                    'status'          => $request->status,
                    'name'            => $request->name,
                    'type'            => $request->type,
                    'amount'          => $request->amount,
                    'payedamount'     => $request->payedamount,
                    'payway'          => $request->payway,
                    'cntidentity'     => $request->cntidentity,
                    'contractcompany' => $request->contractcompany,
                    'contractor'      => $request->contractor,
                    'contractortel'   => $request->contractortel,
                    'signdate'        => $request->signdate,
                    'bdate'           => $request->bdate,
                    'edate'           => $request->edate,
                    'moneyflow'       => $request->moneyflow,
                    'dutyperson'      => $request->dutyperson,
                    'dutypersontel'   => $request->dutypersontel
                ]);
                if ($ok)
                {
                    return $this->success();
                } else
                {
                    return $this->error();
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
            throw  $exception;
        }

    }

    public function upload_contract(Request $request)
    {
        try
        {
            if (is_null($request->contractid))
            {
                return [
                    'code' => 0,
                    'msg'  => '合同Id参数错误！'
                ];
            }
            $file = $request->file('file');
            $newfilename = Uuid::uuid1()->getHex() . '.' . $file->clientExtension();
            $tmpFile = $file->getRealPath();
            $issaved = Storage::disk('local')->put('/contract/' . $newfilename, file_get_contents($tmpFile));
            if ($issaved)
            {
                $contractfile = ContractFile::create([
                    'contractid' => $request->contractid,
                    'file'       => $newfilename,
                    'filetype'   => $file->clientExtension(),
                    'filesize'   => $file->getSize(),
                    'filename'   => $file->getClientOriginalName(),
                    'adduserid'  => Auth::id(),
                    'addtime'    => now()
                ]);
                if ($contractfile->id > 0)
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
            throw  $exception;
        }

    }

    public function remove_contractfile(Request $request)
    {
        try
        {
            if (!is_null($request->filename))
            {
                $filename = $request->filename;
                $ok = Storage::disk('local')->delete('/contract/' . $filename);
                if ($ok)
                {
                    $cnt = ContractFile::where('file', $filename)->delete();
                    if ($cnt > 0)
                    {
                        return $this->success();
                    } else
                    {
                        return $this->error();
                    }
                }
            } else
            {
                return [
                    'code' => 0,
                    'msg'  => '文件名参数错误'
                ];
            }
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function getcontractno(Request $request)
    {
        try
        {
            $cnt = Contract::count('id') + 1;
            $code = 'HT' . str_pad($cnt, 5, '0', STR_PAD_LEFT);
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $code
            ];
        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    public function getcontractfiles(Request $request)
    {
        try
        {
            $contract = Contract::find($request->id);
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $contract->contractfiles()->get()
            ];

        } catch (Exception $exception)
        {
            throw  $exception;
        }

    }

    private function getmaxno($code)
    {

    }
}
