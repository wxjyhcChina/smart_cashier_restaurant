<?php

namespace App\Http\Controllers\Backend\ConsumeOrder;

use App\Http\Requests\Backend\ConsumeOrder\ManageConsumeOrderRequest;
use App\Modules\Enums\ConsumeOrderStatus;
use App\Modules\Models\Agent\RestaurantUser;
use App\Modules\Models\ConsumeOrder\ConsumeOrder;
use App\Modules\Models\DinningTime\DinningTime;
use App\Modules\Models\PayMethod\PayMethod;
use App\Repositories\Backend\ConsumeOrder\ConsumeOrderRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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

        $dinningTime = DinningTime::where('restaurant_id', $user->restaurant_id)->get()->pluck('name', 'id')->toArray();
        $restaurantUser = RestaurantUser::where('restaurant_id', $user->restaurant_id)->get()->pluck('username', 'id')->toArray();

        $dinningTime = $this->appendNullOption($dinningTime);
        $restaurantUser = $this->appendNullOption($restaurantUser);
        $payMethod = $this->appendNullOption($this->getPayMethod($user->restaurant_id));

        return view('backend.consumeOrder.index')
            ->withStartTime($start_time)
            ->withEndTime($end_time)
            ->withDinningTime($dinningTime)
            ->withPayMethod($payMethod)
            ->withRestaurantUser($restaurantUser);
    }

    /**
     * @param ManageConsumeOrderRequest $request
     * @return string
     */
    public function searchOrder(ManageConsumeOrderRequest $request)
    {
        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');
        $dinning_time_id = $request->get('dinning_time_id');
        $pay_method= $request->get('pay_method');
        $restaurant_user_id= $request->get('restaurant_user_id');

        $query = ConsumeOrder::where('created_at', '>=', $start_time)
            ->where('created_at', '<=', $end_time)
            ->where('status', ConsumeOrderStatus::COMPLETE);

        if ($dinning_time_id != null)
        {
            $query->where('dinning_time_id', $dinning_time_id);
        }

        if ($pay_method != null)
        {
            $query->where('pay_method', $pay_method);
        }

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
