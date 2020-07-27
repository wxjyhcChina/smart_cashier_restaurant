<?php

namespace App\Http\Controllers\Backend\ConsumeOrder;

use App\Http\Requests\Backend\ConsumeOrder\ManageConsumeOrderRequest;
use App\Modules\Enums\ConsumeOrderStatus;
use App\Modules\Enums\PayMethodType;
use App\Modules\Models\Restaurant\RestaurantUser;
use App\Modules\Models\ConsumeOrder\ConsumeOrder;
use App\Modules\Models\DinningTime\DinningTime;
use App\Modules\Models\PayMethod\PayMethod;
use App\Repositories\Backend\ConsumeOrder\ConsumeOrderRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ConsumeOrderController extends Controller
{
    /**
     * @var ConsumeOrderRepository
     */
    private $consumeOrderRepo;

    /**
     * ConsumeOrderTableController constructor.
     * @param $consumeOrderRepo
     */
    public function __construct(ConsumeOrderRepository $consumeOrderRepo)
    {
        $this->consumeOrderRepo = $consumeOrderRepo;
    }

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
     * @param ManageConsumeOrderRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(ManageConsumeOrderRequest $request)
    {
        //
        $user = Auth::User();
        $start_time = date('Y-m-d 00:00:00');
        $end_time = date('Y-m-d H:i:s');

        //$restaurantUser = RestaurantUser::where('restaurant_id', $user->restaurant_id)->get()->pluck('username', 'id')->toArray();
        $restaurantUser = RestaurantUser::where('shop_id', $user->shop_id)->get()->pluck('username', 'id')->toArray();

        $restaurantUser = $this->appendNullOption($restaurantUser);

        return view('backend.consumeOrder.index')
            ->withStartTime($start_time)
            ->withEndTime($end_time)
            ->withRestaurantUser($restaurantUser);
    }

    public function getDinningTimeStatistics(Request $request)
    {
        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');

        $orders = ConsumeOrder::where('created_at', '>=', $start_time)
            ->where('created_at', '<=', $end_time)
            ->where('status', ConsumeOrderStatus::COMPLETE)
            ->whereNotNull('dinning_time_id')
            ->get();

        $user = Auth::User();
        $dinningTimes = DinningTime::where('restaurant_id', $user->restaurant_id)->get();

        $dinningTimeData = [];
        foreach ($dinningTimes as $dinningTime)
        {
            $data = [
                'id' => $dinningTime->id,
                'name' => $dinningTime->name,
                'total' => 0,
                'total_count' => 0,
                'alipay' => 0,
                'alipay_count' => 0,
                'wechat' => 0,
                'wechat_count' => 0,
                'cash' => 0,
                'cash_count' => 0,
                'card' => 0,
                'card_count' => 0
            ];

            foreach ($orders as $order)
            {
                if ($order->dinning_time_id == $dinningTime->id)
                {
                    $data['total'] += $order->discount_price;
                    $data['total_count'] ++;

                    switch ($order->pay_method)
                    {
                        case PayMethodType::CASH:
                            $data['cash'] += $order->discount_price;
                            $data['cash_count'] ++;
                            break;
                        case PayMethodType::CARD:
                            $data['card'] += $order->discount_price;
                            $data['card_count'] ++;
                            break;
                        case PayMethodType::ALIPAY:
                            $data['alipay'] += $order->discount_price;
                            $data['alipay_count'] ++;
                            break;
                        case PayMethodType::WECHAT_PAY:
                            $data['wechat'] += $order->discount_price;
                            $data['wechat_count'] ++;
                            break;
                    }
                }
            }

            array_push($dinningTimeData, $data);
        }

        return $dinningTimeData;
    }

    /**
     * @param ManageConsumeOrderRequest $request
     * @return string
     */
    public function searchOrder(ManageConsumeOrderRequest $request)
    {
        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');
        $restaurant_user_id= $request->get('restaurant_user_id');

        $query = ConsumeOrder::where('created_at', '>=', $start_time)
            ->where('created_at', '<=', $end_time)
            ->where('status', ConsumeOrderStatus::COMPLETE);

        if ($restaurant_user_id != null)
        {
            $query->where('restaurant_user_id', $restaurant_user_id);
        }

        $result['order_count']= $query->count();
        $result['money'] = $query->sum('discount_price');

        return json_encode($result);
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
     * @param  ConsumeOrder $consumeOrder
     * @param  ManageConsumeOrderRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ConsumeOrder $consumeOrder, ManageConsumeOrderRequest $request)
    {
        //
        Log::info("show:".json_encode($consumeOrder));
        return view('backend.consumeOrder.info')->withConsumeOrder($consumeOrder);
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
