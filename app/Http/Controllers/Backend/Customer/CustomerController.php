<?php

namespace App\Http\Controllers\Backend\Customer;

use App\Exceptions\GeneralException;
use App\Http\Requests\Backend\Customer\ManageCustomerRequest;
use App\Http\Requests\Backend\Customer\StoreCustomerRequest;
use App\Http\Requests\Backend\Customer\UpdateCustomerBalanceRequest;
use App\Modules\Models\Customer\Customer;
use App\Repositories\Backend\ConsumeCategory\ConsumeCategoryRepository;
use App\Repositories\Backend\Customer\CustomerRepository;
use App\Repositories\Backend\Department\DepartmentRepository;
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
     * @var DepartmentRepository
     */
    private $departmentRepo;

    /**
     * @var ConsumeCategoryRepository
     */
    private $consumeCategoryRepo;

    /**
     * CustomerController constructor.
     * @param CustomerRepository $customerRepo
     * @param DepartmentRepository $departmentRepo
     * @param ConsumeCategoryRepository $consumeCategoryRepo
     */
    public function __construct(CustomerRepository $customerRepo,
                                DepartmentRepository $departmentRepo,
                                ConsumeCategoryRepository $consumeCategoryRepo)
    {
        $this->customerRepo = $customerRepo;
        $this->departmentRepo = $departmentRepo;
        $this->consumeCategoryRepo = $consumeCategoryRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @param ManageCustomerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(ManageCustomerRequest $request)
    {
        //
        return view('backend.customer.index');
    }


    /**
     * @param $models
     * @return array
     */
    private function getModelArray($models)
    {
        $result = array(null => '请选择');
        foreach ($models as $model)
        {
            $result[$model->id] = $model->name;
        }

        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ManageCustomerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(ManageCustomerRequest $request)
    {
        //
        $departments = $this->departmentRepo->getAll();
        $consumeCategories = $this->consumeCategoryRepo->getAll();

        return view('backend.customer.create')
            ->withDepartments($this->getModelArray($departments))
            ->withConsumeCategories($this->getModelArray($consumeCategories));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCustomerRequest  $request
     * @return \Illuminate\Http\Response
     * @throws GeneralException
     */
    public function store(StoreCustomerRequest $request)
    {
        //
        $input = $request->all();
        $input['restaurant_id'] = Auth::User()->restaurant_id;

        $this->customerRepo->create($input);

        return redirect()->route('admin.customer.index')->withFlashSuccess(trans('alerts.backend.customer.created'));

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
     * @param  Customer $customer
     * @param ManageCustomerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer, ManageCustomerRequest $request)
    {
        $departments = $this->departmentRepo->getAll();
        $consumeCategories = $this->consumeCategoryRepo->getAll();
        //
        return view('backend.customer.edit')
            ->withCustomer($customer)
            ->withDepartments($this->getModelArray($departments))
            ->withConsumeCategories($this->getModelArray($consumeCategories));
    }


    /**
     * @param Customer $customer
     * @param ManageCustomerRequest $request
     * @return mixed
     * @throws \App\Exceptions\Api\ApiException
     */
    public function update(Customer $customer, ManageCustomerRequest $request)
    {
        //
        $this->customerRepo->update($customer, $request->all());

        return redirect()->route('admin.customer.index')->withFlashSuccess(trans('alerts.backend.customer.updated'));
    }

    /**
     * @param Customer $customer
     * @param ManageCustomerRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function accountRecords(Customer $customer, ManageCustomerRequest $request)
    {
        return view('backend.customer.accountRecord')->withCustomer($customer);
    }

    /**
     * @param Customer $customer
     * @param ManageCustomerRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function consumeOrders(Customer $customer, ManageCustomerRequest $request)
    {
        return view('backend.customer.consumeOrder')->withCustomer($customer);
    }

    /**
     * @param ManageCustomerRequest $request
     * @return mixed
     */
    public function changeAllBalance(ManageCustomerRequest $request)
    {
        return view('backend.customer.change-all-balance');
    }

    /**
     * @param UpdateCustomerBalanceRequest $request
     * @return mixed
     */
    public function changeAllBalanceStore(UpdateCustomerBalanceRequest $request)
    {
        $user = Auth::User();
        $this->customerRepo->changeAllBalance($request->get('source'), $request->get('balance'), $user->restaurant_id);

        return redirect()->route('admin.customer.index')->withFlashSuccess(trans('alerts.backend.customer.updated_balance'));
    }

    /**
     * @param Customer $customer
     * @param ManageCustomerRequest $request
     * @return mixed
     */
    public function changeBalance(Customer $customer, ManageCustomerRequest $request)
    {
        return view('backend.customer.change-balance')
            ->withCustomer($customer);
    }

    /**
     * @param Customer $customer
     * @param UpdateCustomerBalanceRequest $request
     * @return mixed
     */
    public function changeBalanceStore(Customer $customer, UpdateCustomerBalanceRequest $request)
    {
        $this->customerRepo->changeBalance($customer, $request->get('source'), $request->get('balance'));

        return redirect()->route('admin.customer.accountRecords', $customer)->withFlashSuccess(trans('alerts.backend.customer.updated_balance'));
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
