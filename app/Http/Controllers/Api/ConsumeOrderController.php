<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ConsumeOrder\ConsumeOrderQueryRequest;
use App\Http\Requests\Api\ConsumeOrder\CreateOrderRequest;
use App\Http\Requests\Api\ConsumeOrder\StatisticsQueryRequest;
use App\Modules\Models\ConsumeOrder\ConsumeOrder;
use App\Repositories\Api\ConsumeOrder\ConsumeOrderRepository;
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
     * ConsumeOrderController constructor.
     * @param $consumeOrderRepo
     */
    public function __construct(ConsumeOrderRepository $consumeOrderRepo)
    {
        $this->consumeOrderRepo = $consumeOrderRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function index(ConsumeOrderQueryRequest $request)
    {
        //
        $user = Auth::User();

        $input = $request->all();
        $orders = $this->consumeOrderRepo->getByShop($user->shop_id, $input);

        return $this->responseSuccessWithObject($orders);
    }

    /**
     * @param StatisticsQueryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistics(StatisticsQueryRequest $request)
    {
        $user = Auth::User();

        $input = $request->all();
        $input['shop_id']=$user->shop_id;
        $result = $this->consumeOrderRepo->statistics($user->id, $input);

        $user['statistics'] = $result;

        return $this->responseSuccessWithObject($user);
    }

    /**
     * @param CreateOrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\ApiException
     */
    public function preCreate(CreateOrderRequest $request)
    {
        $input = $request->all();
        $input['restaurant_id'] = Auth::User()->restaurant_id;
        $input['shop_id'] = Auth::User()->shop_id;

        $response = $this->consumeOrderRepo->preCreate($input);

        return $this->responseSuccess($response);
    }

    /**
     * @param CreateOrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\ApiException
     */
    public function store(CreateOrderRequest $request)
    {
        //
        $input = $request->all();
        $user = Auth::User();
        $input['restaurant_id'] = $user->restaurant_id;
        $input['shop_id'] = $user->shop_id;
        $input['restaurant_user_id'] = $user->id;

        $order = $this->consumeOrderRepo->create($input);

        return $this->responseSuccess($order);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function latestOrder(Request $request)
    {
        //$restaurant_id = Auth::User()->restaurant_id;
        $shop_id = Auth::User()->shop_id;
        $order = $this->consumeOrderRepo->latestOrder($shop_id);

        return $this->responseSuccess($order);
    }

    //TODO:policy
    /**
     * @param ConsumeOrder $consumeOrder
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\ApiException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function pay(ConsumeOrder $consumeOrder, Request $request)
    {
        $consumeOrder = $this->consumeOrderRepo->pay($consumeOrder, $request->all());
        //Log::info("consumeOrder:".json_encode($consumeOrder));
        return $this->responseSuccess($consumeOrder);
    }

    /**
     * @param ConsumeOrder $consumeOrder
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function close(ConsumeOrder $consumeOrder, Request $request)
    {
        $consumeOrder = $this->consumeOrderRepo->close($consumeOrder);

        return $this->responseSuccess($consumeOrder);
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
