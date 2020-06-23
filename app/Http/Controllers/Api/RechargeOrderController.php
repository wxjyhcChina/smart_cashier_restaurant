<?php

namespace App\Http\Controllers\Api;

use App\Common\Alipay;
use App\Common\Utils;
use App\Exceptions\Api\ApiException;
use App\Modules\Models\RechargeOrder\RechargeOrder;
use App\Repositories\Api\RechargeOrder\RechargeOrderRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RechargeOrderController extends Controller
{
    /**
     * @var RechargeOrderRepository
     */
    private $rechargeOrderRepo;

    /**
     * ConsumeOrderController constructor.
     * @param $rechargeOrderRepo
     */
    public function __construct(RechargeOrderRepository $rechargeOrderRepo)
    {
        $this->rechargeOrderRepo = $rechargeOrderRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = Auth::User();
        //$orders = $this->rechargeOrderRepo->getByRestaurant($user->restaurant_id);
        $orders = $this->rechargeOrderRepo->getByShop($user->shop_id);

        return $this->responseSuccess($orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws ApiException
     */
    public function store(Request $request)
    {
        //
        $input = $request->all();
        $user = Auth::User();
        $input['restaurant_id'] = $user->restaurant_id;
        $input['shop_id'] = $user->shop_id;
        $input['restaurant_user_id'] = $user->id;

        $order = $this->rechargeOrderRepo->create($input);

        return $this->responseSuccess($order);
    }

    /**
     * @param RechargeOrder $rechargeOrder
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ApiException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function pay(RechargeOrder $rechargeOrder, Request $request)
    {
        $rechargeOrder = $this->rechargeOrderRepo->pay($rechargeOrder, $request->get('barcode'));

        return $this->responseSuccess($rechargeOrder);
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
