<?php

namespace App\Http\Controllers\Backend\RechargeOrder;

use App\Http\Requests\Backend\RechargeOrder\ManageRechargeOrderRequest;
use App\Modules\Enums\PayMethodType;
use App\Modules\Enums\RechargeOrderStatus;
use App\Modules\Models\PayMethod\PayMethod;
use App\Modules\Models\RechargeOrder\RechargeOrder;
use App\Modules\Models\Restaurant\Restaurant;
use App\Modules\Models\Restaurant\RestaurantUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class RechargeOrderController extends Controller
{
    private function appendNullOption($array)
    {
        $result = [];
        $result[null] = '请选择';

        $result = $result + $array;
        return $result;
    }

    private function getPayMethod($restaurant_id)
    {
        $payMethods = PayMethod::where('restaurant_id', $restaurant_id)->where('enabled', 1)->get();

        $array = [];
        foreach ($payMethods as $payMethod)
        {
            $array[$payMethod->method] = $payMethod->getShowMethodName();
        }

        return $array;
    }


    /**
     * Display a listing of the resource.
     *
     * @param ManageRechargeOrderRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(ManageRechargeOrderRequest $request)
    {
        //
        $user = Auth::User();
        $start_time = date('Y-m-d 00:00:00');
        $end_time = date('Y-m-d H:i:s');

        $restaurantUser = RestaurantUser::where('restaurant_id', $user->restaurant_id)->get()->pluck('username', 'id')->toArray();

        $restaurantUser = $this->appendNullOption($restaurantUser);
//        $payMethod = $this->appendNullOption($this->getPayMethod($user->restaurant_id));

        return view('backend.RechargeOrder.index')
            ->withStartTime($start_time)
            ->withEndTime($end_time)
//            ->withPayMethod($payMethod)
            ->withRestaurantUser($restaurantUser);
    }

    /**
     * @param ManageRechargeOrderRequest $request
     * @return string
     */
    public function searchOrder(ManageRechargeOrderRequest $request)
    {
        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');
//        $pay_method= $request->get('pay_method');
        $restaurant_user_id= $request->get('restaurant_user_id');

        $query = $this->getQuery($start_time, $end_time, $restaurant_user_id);

        $detail = $this->getDetailResult($query);

        return json_encode($detail);
    }

    private function getQuery($start_time, $end_time, $restaurant_user_id)
    {
        $query = RechargeOrder::where('created_at', '>=', $start_time)
            ->where('created_at', '<=', $end_time)
            ->where('status', RechargeOrderStatus::COMPLETE);

        if ($restaurant_user_id != null)
        {
            $query->where('restaurant_user_id', $restaurant_user_id);
        }

        return $query;
    }

    private function getDetailResult($query){


//        if ($pay_method != null)
//        {
//            $query->where('pay_method', $pay_method);
//        }
        $result['order_count']= (clone $query)->count();
        $result['money'] = (clone $query)->sum('money');
        $result['cash'] = (clone $query)->where('pay_method', PayMethodType::CASH)->sum('money');
        $result['cash_count'] = (clone $query)->where('pay_method', PayMethodType::CASH)->count();
        $result['alipay'] = (clone $query)->where('pay_method', PayMethodType::ALIPAY)->sum('money');
        $result['alipay_count'] = (clone $query)->where('pay_method', PayMethodType::ALIPAY)->count();
        $result['wechat'] = (clone $query)->where('pay_method', PayMethodType::WECHAT_PAY)->sum('money');
        $result['wechat_count'] = (clone $query)->where('pay_method', PayMethodType::WECHAT_PAY)->count();

        return $result;
    }


    public function export(Request $request)
    {
        $user = Auth::User();
        $restaurant = Restaurant::where('id', $user->restaurant_id)->first();
        $timeArray = explode(' - ', $request->get('search_time'));

        $start_time = $timeArray[0];
        $end_time = $timeArray[1];
//        $pay_method= $request->get('pay_method');
        $restaurant_user_id= $request->get('restaurant_user_id');

        $query = $this->getQuery($start_time, $end_time, $restaurant_user_id);

        $detail = $this->getDetailResult($query);
        $orders = $query->get();

        Excel::create('充值记录', function($excel) use ($restaurant, $detail, $orders, $start_time, $end_time){
            $excel->sheet('充值记录', function($sheet) use ($restaurant, $detail, $orders, $start_time, $end_time){

                $sheet->setAutoSize(true);
                $sheet->mergeCells('A1:H1');
                $sheet->mergeCells('A2:H2');

                $sheet->cells('A1:H1', function($cells) {
                    // manipulate the cell
                    $cells->setAlignment('center');
                });

                $sheet->cells('A2:H2', function($cells) {
                    // manipulate the cell
                    $cells->setAlignment('center');
                });

                // Sheet manipulation
                $sheet->row(1, array($restaurant->name.'充值统计'));
                $sheet->row(2, array('开始时间'.$start_time.' '.'结束时间'.$end_time));

                $sheet->appendRow(array(
                    '编号', '订单编号', '用户编号', '卡编号', '支付方式', '价格', '充值时间', '营业员'
                ));

                $rowNumber = 3;
                foreach ($orders as $order)
                {
                    $rowNumber++;
                    $sheet->appendRow(array(
                        $order->id, $order->order_id,
                        $order->customer->user_name, $order->card->number,
                        $order->getShowPayMethodAttribute(), $order->money,
                        $order->created_at, $order->restaurant_user->username
                    ));
                }

                $rowNumber++;
                $sheet->mergeCells("A$rowNumber:H$rowNumber");
                $sheet->row($rowNumber, array('总充值金额: '.$detail['money']));

                $rowNumber++;
                $sheet->mergeCells("A$rowNumber:H$rowNumber");
                $sheet->row($rowNumber, array('现金充值金额: '.$detail['cash']));

                $rowNumber++;
                $sheet->mergeCells("A$rowNumber:H$rowNumber");
                $sheet->row($rowNumber, array('支付宝充值金额: '.$detail['alipay']));

                $rowNumber++;
                $sheet->mergeCells("A$rowNumber:H$rowNumber");
                $sheet->row($rowNumber, array('微信充值金额: '.$detail['wechat']));

                $rowNumber++;
                $sheet->mergeCells("A$rowNumber:H$rowNumber");
                $sheet->cells("A$rowNumber:H$rowNumber", function($cells) {
                    // manipulate the cell
                    $cells->setAlignment('right');
                });
                $sheet->row($rowNumber, array('制表时间:'.date('Y-m-d H:i:s')));

            });
        })->export('xls');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
