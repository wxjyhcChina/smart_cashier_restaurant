<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Customer\StoreCustomerRequest;
use App\Modules\Models\Customer\Customer;
use App\Repositories\Api\Customer\CustomerRepository;
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
     * CustomerController constructor.
     * @param CustomerRepository $customerRepo
     */
    public function __construct(CustomerRepository $customerRepo)
    {
        $this->customerRepo = $customerRepo;
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

        $departments = $this->customerRepo->getByRestaurant($user->restaurant_id);

        return $this->responseSuccess($departments);
    }

    /**
     * @param StoreCustomerRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\ApiException
     */
    public function create(StoreCustomerRequest $request)
    {
        //
        $input = $request->all();
        $input['restaurant_id'] = Auth::User()->restaurant_id;

        $dinningTime = $this->customerRepo->create($input);

        return $this->responseSuccessWithObject($dinningTime);
    }

    /**
     * @param Request $request
     */
    public function accountRecords(Request $request)
    {

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
