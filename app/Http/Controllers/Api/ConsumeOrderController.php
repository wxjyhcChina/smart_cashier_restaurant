<?php

namespace App\Http\Controllers\Api;

use App\Modules\Models\ConsumeOrder\ConsumeOrder;
use App\Repositories\Api\ConsumeOrder\ConsumeOrderRepository;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = Auth::User();

        $orders = $this->consumeOrderRepo->getByRestaurant($user->restaurant_id);

        return $this->responseSuccess($orders);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\ApiException
     */
    public function preCreate(Request $request)
    {
        $input = $request->all();
        $input['restaurant_id'] = Auth::User()->restaurant_id;

        $response = $this->consumeOrderRepo->preCreate($input);

        return $this->responseSuccess($response);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\ApiException
     */
    public function store(Request $request)
    {
        //
        $input = $request->all();
        $user = Auth::User();
        $input['restaurant_id'] = $user->restaurant_id;
        $input['restaurant_user_id'] = $user->id;

        $order = $this->consumeOrderRepo->create($input);

        return $this->responseSuccess($order);
    }


    public function latestOrder(Request $request)
    {
        $restaurant_id = Auth::User()->restaurant_id;

        $order = $this->consumeOrderRepo->latestOrder($restaurant_id);

        return $this->responseSuccess($order);
    }

    //TODO:policy
    public function pay(ConsumeOrder $order, Request $request)
    {
        $order = $this->consumeOrderRepo->pay($order, $request->all());

        return $this->responseSuccess();
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
