<?php

namespace App\Http\Controllers\Api\Hotel;

use App\Code\DataPermission;
use App\Code\RemainRoomTypeQty;
use App\Code\TSql;
use App\Http\Controllers\Controller;
use App\Models\HotelBook;
use App\Models\Meal;
use App\Models\MealBook;
use App\Models\Organize;
use App\Models\RoomType;
use App\Models\Ship;
use App\Models\ShipRoomType;
use App\Models\Sql;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HotelController extends Controller
{
    use DataPermission;
    use TSql;
    use RemainRoomTypeQty;

    /*
     * 启用状态邮轮
     */
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

    public function allshiplist(Request $request)
    {
        try
        {
            $query = Ship::query();
            $query->when(!is_null($request->keyword), function (Builder $q) use ($request)
            {
                $q->where('name', 'like', '%' . $request->keyword . '%');
            });
            $query->when(!is_null($request->status), function (Builder $q) use ($request)
            {
                $q->where('status', '=', $request->status);
            });
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

    public function enable_ship(Request $request)
    {
        try
        {
            $shipid = $request->id ?? 0;
            $ok = Ship::find($shipid)->update([
                'status' => $request->status
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
     * 邮轮房型
     */
    public function add_shiproomtype(Request $request)
    {
        try
        {
            DB::beginTransaction();
            ShipRoomType::where('shipno', $request->shipno)->delete();
            foreach ($request->roomtypes as $roomtype)
            {
                $price = RoomType::find($roomtype)->price;
                ShipRoomType::create([
                    'shipno'     => $request->shipno,
                    'roomtypeid' => $roomtype,
                    'price'      => $price,
                    'addtime'    => now(),
                    'addusrid'   => Auth::id()
                ]);
            }
            DB::commit();
            return $this->success();
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
     * 邮轮房型列表
     */
    public function shiproomtype_list(Request $request)
    {
        try
        {
            if (is_null($request->shipno))
            {
                return [
                    'code'   => 1,
                    'msg'    => '请选择邮轮',
                    'result' => []
                ];
            }
            $query = ShipRoomType::query();
            $query->when(!is_null($request->shipno), function (Builder $q) use ($request)
            {
                $q->where('shipno', $request->shipno);
            });
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
     * 代理商列表
     */
    public function agentlist(Request $request)
    {
        try
        {
            $query = Organize::where('orgtype', '=', '05')->where('status', '=', 1);
            $query->when(!is_null($request->id), function (Builder $q) use ($request)
            {
                $q->where('id', $request->id);
            });
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

    //房型列表
    public function roomtypelist(Request $request)
    {
        try
        {
            $query = RoomType::query();
            $query->when(!is_null($request->name), function (Builder $q) use ($request)
            {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
            $query->when(!is_null($request->status), function (Builder $q) use ($request)
            {
                $q->where('status', $request->status);
            });
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

    //添加房型
    public function addroomtype(Request $request)
    {
        try
        {
            $roomtype = RoomType::create([
                'status'    => 1,
                'name'      => $request->name,
                'shortname' => $request->shortname,
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
                'name'      => $request->name,
                'shortname' => $request->shortname,
                'price'     => $request->price,
                'totalqty'  => $request->totalqty
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

    //客房预订
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

            $postdata = [];
            $totalamount = 0;
            $errors = [];
            $roomtypeids = collect($request->details)->pluck('roomtypeid')->toArray();
            $remain_qty = collect($this->roomtype_remain_qty($request->shipno, $orgid, $roomtypeids));
            foreach ($request->details as $room)
            {
                $totalamount = $totalamount + (double)$room['amount'];
                array_push($postdata, [
                    'roomtypeid'     => $room['roomtypeid'],
                    'qty'            => $room['qty'],
                    'price'          => $room['price'],
                    'customer_price' => $room['customer_price'],
                    'amount'         => $room['amount'],
                ]);
                //
                if ($remain_qty->where('roomtypeid', $room['roomtypeid'])->count() > 0)
                {
                    $totalqty = $remain_qty->where('roomtypeid', $room['roomtypeid'])->first()->qty;
                    $qty1 = $remain_qty->where('roomtypeid', $room['roomtypeid'])->first()->qty1;
                    $rqty = $remain_qty->where('roomtypeid', $room['roomtypeid'])->first()->rqty;
                } else
                {
                    $totalqty = 0;
                    $qty1 = 0;
                    $rqty = 0;
                }
                $cnt = $rqty - $room['qty'];
                if ($cnt < 0)
                {
                    $roomtype = RoomType::find($room['roomtypeid']);
                    array_push($errors, [
                        'roomtypename' => $roomtype->name,
                        'totalqty'     => $totalqty,
                        'bookedqty'    => $qty1,
                        'remainqty'    => $rqty
                    ]);
                }
            }
            if (count($errors) > 0)
            {
                $msg = '';
                foreach ($errors as $error)
                {
                    $msg = $msg . $error['roomtypename'] . '总数:' . $error['totalqty'] . '已订:' . $error['bookedqty'];
                }
                return [
                    'code'   => 0,
                    'msg'    => $msg . '预订超标',
                    'result' => $errors
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
     * 修改客房预订信息
     */
    public function edit_hotelbook(Request $request)
    {
        try
        {
            $id = $request->id ?? 0;
            $book = HotelBook::find($id);
            $dates = $request->hoteldate;
            if ($dates[0] < date('Y-m-d'))
            {
                return [
                    'code' => 0,
                    'msg'  => '入住时间须大于等于当前时间'
                ];
            }
            DB::beginTransaction();

            $postdata = [];
            $totalamount = 0;
            $roomtypeids = $roomtypeids = collect($request->details)->pluck('roomtypeid')->toArray();
            $errors = [];
            $remain_qty = collect($this->roomtype_remain_qty($book->shipno, $book->orgid, $roomtypeids));
            foreach ($request->details as $room)
            {
                $totalamount = $totalamount + (double)$room['amount'];
                array_push($postdata, [
                    'roomtypeid' => $room['roomtypeid'],
                    'qty'        => $room['qty'],
                    'price'      => $room['price'],
                    'amount'     => $room['amount'],
                ]);
                //
                if ($remain_qty->where('roomtypeid', $room['roomtypeid'])->count() > 0)
                {
                    $totalqty = $remain_qty->where('roomtypeid', $room['roomtypeid'])->first()->qty;
                    $qty1 = $remain_qty->where('roomtypeid', $room['roomtypeid'])->first()->qty1;
                    $rqty = $remain_qty->where('roomtypeid', $room['roomtypeid'])->first()->rqty;
                } else
                {
                    $totalqty = 0;
                    $qty1 = 0;
                    $rqty = 0;
                }
                $cnt = $rqty - $room['qty'];
                if ($cnt < 0)
                {
                    $roomtype = RoomType::find($room['roomtypeid']);
                    array_push($errors, [
                        'roomtypename' => $roomtype->name,
                        'totalqty'     => $totalqty,
                        'bookedqty'    => $qty1,
                        'remainqty'    => $rqty
                    ]);
                }
            }
            if (count($errors) > 0)
            {
                $msg = '';
                foreach ($errors as $error)
                {
                    $msg = $msg . $error['roomtypename'] . '总数:' . $error['totalqty'] . '已订:' . $error['bookedqty'];
                }
                return [
                    'code'   => 0,
                    'msg'    => $msg . '预订超标',
                    'result' => $errors
                ];
            }
            $ok = $book->update([
                'shipno'    => $request->shipno,
                'bdate'     => $dates[0],
                'edate'     => $dates[1],
                'bookname'  => $request->bookname,
                'booktel'   => $request->booktel,
                'bookcount' => $request->bookcount,
                'booknote'  => $request->booknote,
            ]);
            $book->details()->delete();
            $book->details()->createMany($postdata);
            $book->update([
                'amount' => $totalamount
            ]);
            DB::commit();
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
                    $q->orWhere(function (Builder $s) use ($request)
                    {
                        $s->where('bdate', '<=', $request->checkindate[0])
                            ->where('edate', '>', $request->checkindate[0]);

                    });
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
            $query->when(!is_null($request->agentid), function (Builder $q) use ($request)
            {
                $q->where('orgid', $request->agentid);
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
     * dd
     */
    public function bookroomlist_new(Request $request)
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
                    $q->orWhere(function (Builder $s) use ($request)
                    {
                        $s->where('bdate', '<=', $request->checkindate[0])
                            ->where('edate', '>', $request->checkindate[0]);

                    });
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
            $query->when(!is_null($request->agentid), function (Builder $q) use ($request)
            {
                $q->where('orgid', $request->agentid);
            });
            var_dump($query->toSql());
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
     * 客房预订确认
     */
    public function bookroom_ok(Request $request)
    {
        try
        {
            $id = $request->id ?? 0;
            $ok = HotelBook::where('id', $id)->where('status', '=', 1)->update([
                'status' => $request->status
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
     * 套餐列表
     */

    public function meallist(Request $request)
    {
        try
        {
            $pagesize = $request->pagesize ?? 15;
            $query = Meal::where('status', '=', 1);
            $query->when(!is_null($request->status), function (Builder $q) use ($request)
            {
                $q->where('status', $request->status);
            });
            $query->when(!is_null($request->keyword), function (Builder $q) use ($request)
            {
                $q->where('name', 'like', '%' . $request->keyword . '%');
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

    /*
     * 新增套餐
     */
    public function addmeal(Request $request)
    {
        try
        {
            $orgid = DB::table('userorg')->where('userid', Auth::id())->value('departmentid') ?? 0;
            $meal = Meal::create([
                'shipno'    => $request->shipno,
                'name'      => $request->name,
                'price'     => $request->price,
                'note'      => $request->note,
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
                'shipno' => $request->shipno,
                'name'   => $request->name,
                'price'  => $request->price,
                'note'   => $request->note,
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
                'shipno'    => $request->shipno,
                'mealdate'  => date('Y-m-d', strtotime($request->mealdate)),
                'bookname'  => $request->bookname,
                'booktel'   => $request->booktel,
                'booknote'  => $request->booknote,
                'orgid'     => $orgid,
                'ispayed'   => $request->ispayed ?? 1,
                'adduserid' => Auth::id(),
                'addtime'   => now()
            ]);
            $postdata = [];
            $total = 0;
            foreach ($request->details as $detail)
            {
                $amount = (double)$detail['qty'] * (double)$detail['price'];
                $total = $total + $amount;
                array_push($postdata, [
                    'mealid' => $detail['mealid'],
                    'price'  => $detail['price'],
                    'qty'    => $detail['qty'],
                    'amount' => $total,
                ]);
            }
            $mealbook->details()->createMany($postdata);
            $mealbook->update([
                'amount' => $total
            ]);
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
     * 用餐预订修改
     */
    public function editmealbook(Request $request)
    {
        try
        {
            $id = $request->id ?? 0;
            $mealbook = MealBook::find($id);
            DB::beginTransaction();
            $ok = $mealbook->update([
                'shipno'   => $request->shipno,
                'mealdate' => $request->mealdate,
                'bookname' => $request->bookname,
                'booktel'  => $request->booktel,
                'booknote' => $request->booknote,
                'ispayed'  => $request->ispayed ?? 1
            ]);
            $postdata = [];
            $total = 0;
            foreach ($request->details as $detail)
            {
                $amount = (double)$detail['qty'] * (double)$detail['price'];
                $total = $total + $amount;
                array_push($postdata, [
                    'mealid' => $detail['mealid'],
                    'price'  => $detail['price'],
                    'qty'    => $detail['qty'],
                    'amount' => $total,
                ]);
            }
            $mealbook->details()->delete();
            $mealbook->details()->createMany($postdata);
            $mealbook->update([
                'amount' => $total
            ]);
            DB::commit();
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
    /*
     * 用餐预订确认
     */
    public function bookmeal_ok(Request $request)
    {
        try
        {
            $id = $request->id ?? 0;
            $ok = MealBook::where('id', $id)->where('status', '=', 1)->update([
                'status' => $request->status
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
     * 当天用房数量
     */


}
