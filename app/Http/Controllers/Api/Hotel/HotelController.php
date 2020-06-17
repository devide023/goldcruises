<?php

namespace App\Http\Controllers\Api\Hotel;

use App\Code\DataPermission;
use App\Http\Controllers\Controller;
use App\Models\HotelBook;
use App\Models\Meal;
use App\Models\MealBook;
use App\Models\RoomType;
use App\Models\Ship;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HotelController extends Controller
{
    use DataPermission;

    public function shiplist(Request $request)
    {
        try
        {
            $query = Ship::where('status', '=', 1)->get();
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $query
            ];
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

    //房型列表
    public function roomtypelist(Request $request)
    {
        try
        {
            $query = RoomType::all();
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $query
            ];

        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

    //添加房型
    public function addroomtype(Request $request)
    {
        try
        {
            $roomtype = RoomType::create([
                'status'    => 1,
                'shipno'    => $request->shipno,
                'name'      => $request->name,
                'price'     => $request->price,
                'totalqty'  => $request->totalqty,
                'addtime'   => now(),
                'adduserid' => \Auth::id()
            ]);
            if ($roomtype->id > 0)
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

    //修改房型
    public function editroomtype(Request $request)
    {
        try
        {
            $roomtype = RoomType::find($request->id);
            $ok = $roomtype->update([
                'shipno'   => $request->shipno,
                'name'     => $request->name,
                'price'    => $request->price,
                'totalqty' => $request->totalqty
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
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

    //预订
    public function addhotelbook(Request $request)
    {
        try
        {
            $orgid = DB::table('userorg')->where('userid', Auth::id())->value('departmentid') ?? 0;
            if (is_null($request->hoteldate))
            {
                return [
                    'code' => 0,
                    'msg'  => '请选择入住时间'
                ];
            }
            DB::beginTransaction();
            $dates = $request->hoteldate;
            if ($dates[0] < date('Y-m-d'))
            {
                return [
                    'code' => 0,
                    'msg'  => '入住时间须大于等于当前时间'
                ];
            }
            $book = HotelBook::create([
                'shipno'    => $request->shipno,
                'bdate'     => $dates[0],
                'edate'     => $dates[1],
                'bookname'  => $request->bookname,
                'booktel'   => $request->booktel,
                'bookcount' => $request->bookcount,
                //人数
                'booknote'  => $request->booknote,
                'adduserid' => \Auth::id(),
                'addtime'   => now(),
                'orgid'     => $orgid
            ]);
            $postdata = [];
            $totalamount = 0;
            foreach ($request->details as $room)
            {
                $totalamount = $totalamount + (double)$room['amount'];
                array_push($postdata, [
                    'roomtypeid' => $room['roomtypeid'],
                    'qty'        => $room['qty'],
                    'price'      => $room['price'],
                    'amount'     => $room['amount'],
                ]);
            }
            $book->details()->createMany($postdata);
            $book->update([
                'amount' => $totalamount
            ]);
            DB::commit();
            if ($book->id > 0)
            {
                return $this->success();
            } else
            {
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

    /*
     * 客房预订列表
     */
    public function bookroomlist(Request $request)
    {
        try
        {
            $pagesize = $request->pagesize;
            $query = HotelBook::query();
            $orgids = $this->current_user_datapermission();
            $query->whereIn('orgid', $orgids);
            if (!is_null($request->checkindate))
            {
                $query->when(count($request->checkindate) == 2, function (Builder $q) use ($request)
                {
                    $q->whereBetween('bdate', [
                        $request->checkindate[0] . ' 0:0:0',
                        $request->checkindate[1] . ' 23:59:59'
                    ]);
                });
            }
            if (!is_null($request->checkoutdate))
            {
                $query->when(count($request->checkoutdate) == 2, function (Builder $q) use ($request)
                {
                    $q->whereBetween('edate', [
                        $request->checkoutdate[0] . ' 0:0:0',
                        $request->checkoutdate[1] . ' 23:59:59'
                    ]);
                });
            }
            $query->when(!is_null($request->tel), function (Builder $q) use ($request)
            {
                $q->where('booktel', 'like', '%' . $request->tel . '%');
            });
            $query->when(!is_null($request->name), function (Builder $q) use ($request)
            {
                $q->where('bookname', 'like', '%' . $request->name . '%');
            });
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $query->orderBy('id', 'desc')->paginate($pagesize)
            ];
        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }


    /*
     * 套餐列表
     */

    public function meallist(Request $request)
    {
        try
        {
            $query = Meal::where('status', '=', 1);
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $query->get()
            ];

        } catch (Exception $exception)
        {
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

    /*
     * 新增套餐
     */
    public function addmeal(Request $request)
    {
        try
        {
            $orgid = DB::table('userorg')->where('userid', Auth::id())->value('departmentid') ?? 0;
            $meal = Meal::create([
                'name'      => $request->name,
                'price'     => $request->price,
                'addtime'   => now(),
                'status'    => 1,
                'adduserid' => Auth::id(),
                'orgid'     => $orgid
            ]);
            if ($meal->id > 0)
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

    /*
     * 修改套餐
     */
    public function editmeal(Request $request)
    {
        try
        {
            $id = $request->id ?? 0;
            $meal = Meal::find($id);
            $ok = $meal->update([
                'name'  => $request->name,
                'price' => $request->price,
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
            return [
                'code' => 0,
                'msg'  => $exception->getMessage()
            ];
        }
    }

    /*
     * 用餐预订
     */
    public function addmealbook(Request $request)
    {
        try
        {
            $orgid = DB::table('userorg')->where('userid', Auth::id())->value('departmentid') ?? 0;
            DB::beginTransaction();
            $mealbook = MealBook::create([
                'bookname'  => $request->bookname,
                'booktel'   => $request->booktel,
                'booknote'  => $request->booknote,
                'orgid'     => $orgid,
                'adduserid' => Auth::id(),
                'addtime'   => now()
            ]);
            $postdata = [];
            foreach ($request->details as $detail)
            {
                array_push($postdata, [
                    'mealid' => $detail['mealid'],
                    'price'  => $detail['price'],
                    'qty'    => $detail['qty'],
                    'amount' => (double)$detail['qty'] * (double)$detail['price'],
                ]);
            }
            $mealbook->details()->createMany($postdata);
            DB::commit();
            if ($mealbook->id > 0)
            {
                return $this->success();
            } else
            {
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

    /*
     * 用餐预订列表
     */
    public function mealbooklist(Request $request)
    {
        try
        {
            $pagesize = $request->pagesize ?? 15;
            $query = MealBook::query();
            $orgids = $this->current_user_datapermission();
            $query->whereIn('orgid', $orgids);
            $query->when(!is_null($request->bookname), function (Builder $q) use ($request)
            {
                $q->where('bookname', 'like', '%' . $request->bookname . '%');
            });
            $query->when(!is_null($request->booktel), function (Builder $q) use ($request)
            {
                $q->where('booktel', 'like', '%' . $request->booktel . '%');
            });
            return [
                'code'   => 1,
                'msg'    => 'ok',
                'result' => $query->orderBy('id', 'desc')->paginate($pagesize)
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
