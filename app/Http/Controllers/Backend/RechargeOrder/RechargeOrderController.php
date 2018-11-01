<?php

namespace App\Http\Controllers\Backend\RechargeOrder;

use App\Http\Requests\Backend\RechargeOrder\ManageRechargeOrderRequest;
use App\Modules\Enums\RechargeOrderStatus;
use App\Modules\Models\PayMethod\PayMethod;
use App\Modules\Models\RechargeOrder\RechargeOrder;
use App\Modules\Models\Restaurant\RestaurantUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
        $payMethod = $this->appendNullOption($this->getPayMethod($user->restaurant_id));

        return view('backend.RechargeOrder.index')
            ->withStartTime($start_time)
            ->withEndTime($end_time)
            ->withPayMethod($payMethod)
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
        $pay_method= $request->get('pay_method');
        $restaurant_user_id= $request->get('restaurant_user_id');

        $query = RechargeOrder::where('created_at', '>=', $start_time)
            ->where('created_at', '<=', $end_time)
            ->where('status', RechargeOrderStatus::COMPLETE);

        if ($pay_method != null)
        {
            $query->where('pay_method', $pay_method);
        }

        if ($restaurant_user_id != null)
        {
            $query->where('restaurant_user_id', $restaurant_user_id);
        }

        $result['order_count']= $query->count();
        $result['money'] = $query->sum('money');

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
