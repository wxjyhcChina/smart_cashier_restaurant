<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Customer\CustomerAccountQueryRequest;
use App\Http\Requests\Api\Customer\StoreCustomerRequest;
use App\Modules\Models\ConsumeOrder\RechargeOrder;
use App\Modules\Models\Customer\Customer;
use App\Repositories\Api\Customer\CustomerRepository;
use App\Repositories\Api\RechargeOrder\RechargeOrderRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * @var CustomerRepository
     */
    private $customerRepo;

    /**
     * @var RechargeOrderRepository
     */
    private $rechargeOrderRepo;

    /**
     * CustomerController constructor.
     * @param CustomerRepository $customerRepo
     * @param RechargeOrderRepository $rechargeOrderRepo
     */
    public function __construct(CustomerRepository $customerRepo, RechargeOrderRepository $rechargeOrderRepo)
    {
        $this->customerRepo = $customerRepo;
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

        //$departments = $this->customerRepo->getByRestaurant($user->restaurant_id);
        $departments = $this->customerRepo->getByRestaurant($user->restaurant_id);

        return $this->responseSuccess($departments);
    }

    /**
     * @param StoreCustomerRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\ApiException
     */
    public function store(StoreCustomerRequest $request)
    {
        //
        $input = $request->all();
        $input['restaurant_id'] = Auth::User()->restaurant_id;
        $input['shop_id'] = Auth::User()->shop_id;

        $dinningTime = $this->customerRepo->create($input);

        return $this->responseSuccessWithObject($dinningTime);
    }

    //TODO:policy
    /**
     * @param Customer $customer
     * @param CustomerAccountQueryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function accountRecords(Customer $customer, CustomerAccountQueryRequest $request)
    {
        $input = $request->all();
        $records = $this->customerRepo->getAccountRecord($customer, $input);

        return $this->responseSuccessWithObject($records);
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
     * @param  int  $idg
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * @param Customer $customer
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\ApiException
     */
    public function update(Customer $customer, Request $request)
    {
        //
        $customer = $this->customerRepo->update($customer, $request->all());

        return $this->responseSuccessWithObject($customer);
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
