<?php

namespace App\Http\Controllers\Backend\Customer;

use App\Exceptions\Api\ApiException;
use App\Exceptions\GeneralException;
use App\Http\Requests\Backend\Customer\ManageCustomerRequest;
use App\Http\Requests\Backend\Customer\StoreCustomerRequest;
use App\Http\Requests\Backend\Customer\UpdateCustomerBalanceRequest;
use App\Modules\Enums\PayMethodType;
use App\Modules\Enums\RechargeOrderStatus;
use App\Modules\Models\Customer\Customer;
use App\Modules\Models\PayMethod\PayMethod;
use App\Repositories\Backend\ConsumeCategory\ConsumeCategoryRepository;
use App\Repositories\Backend\Customer\CustomerRepository;
use App\Repositories\Backend\Department\DepartmentRepository;
use App\Repositories\Backend\RechargeOrder\RechargeOrderRepository;
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
     * @var RechargeOrderRepository
     */
    private $rechargeOrderRepo;

    /**
     * CustomerController constructor.
     * @param CustomerRepository $customerRepo
     * @param DepartmentRepository $departmentRepo
     * @param ConsumeCategoryRepository $consumeCategoryRepo
     * @param RechargeOrderRepository $rechargeOrderRepo
     */
    public function __construct(CustomerRepository $customerRepo,
                                DepartmentRepository $departmentRepo,
                                ConsumeCategoryRepository $consumeCategoryRepo,
                                RechargeOrderRepository $rechargeOrderRepo)
    {
        $this->customerRepo = $customerRepo;
        $this->departmentRepo = $departmentRepo;
        $this->consumeCategoryRepo = $consumeCategoryRepo;
        $this->rechargeOrderRepo = $rechargeOrderRepo;
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
        $user = Auth::User();
        //$departments = $this->departmentRepo->getByRestaurantQuery($user->restaurant_id)->get();
        $departments = $this->departmentRepo->getByShopQuery($user->shop_id)->get();
        //$consumeCategories = $this->consumeCategoryRepo->getByRestaurantQuery($user->restaurant_id)->get();
        $consumeCategories = $this->consumeCategoryRepo->getByShopQuery($user->shop_id)->get();

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
        $input['shop_id'] = Auth::User()->shop_id;

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
        $user = Auth::User();

        //$departments = $this->departmentRepo->getByRestaurantQuery($user->restaurant_id)->get();
        $departments = $this->departmentRepo->getByShopQuery($user->shop_id)->get();
        //$consumeCategories = $this->consumeCategoryRepo->getByRestaurantQuery($user->restaurant_id)->get();
        $consumeCategories = $this->consumeCategoryRepo->getByShopQuery($user->shop_id)->get();
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
     * @param Customer $customer
     * @param $status
     * @param ManageCustomerRequest $request
     * @return mixed
     * @throws ApiException
     */
    public function mark(Customer $customer, $status, ManageCustomerRequest $request)
    {
        $this->customerRepo->update($customer, ['enabled' => $status]);

        return redirect()->route('admin.customer.index')->withFlashSuccess(trans('alerts.backend.customer.updated'));
    }

    public function clearSubsidyBalance()
    {
        return view('backend.customer.clear-subsidy-balance');
    }

    /**
     * @param ManageCustomerRequest $request
     * @return mixed
     * @throws \Throwable
     */
    public function clearSubsidyBalanceStore(ManageCustomerRequest $request)
    {
        $user = Auth::User();
        $ids = [];
        $type = $request->get('type');
        if ($type == 'department')
        {
            $ids = $request->get('department_id');
        }
        else if ($type == 'customer')
        {
            $ids = $request->get('customer_id');
        }

        $this->customerRepo->clearMultipleBalance( $request->get('type'), $ids, $user->restaurant_id);

        return redirect()->route('admin.customer.index')->withFlashSuccess(trans('alerts.backend.customer.updated_balance'));
    }

    /**
     * @param ManageCustomerRequest $request
     * @return mixed
     */
    public function changeMultipleBalance(ManageCustomerRequest $request)
    {
        return view('backend.customer.change-multiple-balance');
    }

    /**
     * @param UpdateCustomerBalanceRequest $request
     * @return mixed
     * @throws \Throwable
     */
    public function changeMultipleBalanceStore(UpdateCustomerBalanceRequest $request)
    {
        $user = Auth::User();
        $ids = [];
        $type = $request->get('type');
        if ($type == 'department')
        {
            $ids = $request->get('department_id');
        }
        else if ($type == 'customer')
        {
            $ids = $request->get('customer_id');
        }

        $this->customerRepo->changeMultipleBalance($request->get('source'), $request->get('balance'), $request->get('type'), $ids, $user->restaurant_id);

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
     * @return array
     */
    private function getEnabledPayMethod($restaurant_id)
    {
        $payMethods = PayMethod::where('restaurant_id', $restaurant_id)->get();

        $array = [];
        foreach ($payMethods as $payMethod) {
            if ($payMethod->method != PayMethodType::CARD)
            {
                $array[$payMethod->method] = [
                    'name' => $payMethod->getShowMethodName(),
                    'enabled' => $payMethod->enabled
                ];
            }
        }

        return $array;
    }

    /**
     * @param Customer $customer
     * @param ManageCustomerRequest $request
     * @return mixed
     */
    public function recharge(Customer $customer, ManageCustomerRequest $request)
    {
        $user = Auth::User();
        $payMethods = $this->getEnabledPayMethod($user->restaurant_id);

        return view('backend.customer.recharge')
            ->withCustomer($customer)
            ->withPayMethods($payMethods);
    }


    /**
     * @param Customer $customer
     * @param ManageCustomerRequest $request
     * @return mixed
     */
    public function bindCard(Customer $customer, ManageCustomerRequest $request)
    {
        return view('backend.customer.bind')->withCustomer($customer);
    }

    /**
     * @param Customer $customer
     * @param ManageCustomerRequest $request
     * @return mixed
     */
    public function unbindCard(Customer $customer, ManageCustomerRequest $request)
    {
        return view('backend.customer.unbind')->withCustomer($customer);
    }

    /**
     * @param Customer $customer
     * @param ManageCustomerRequest $request
     * @return mixed
     */
    public function lostCard(Customer $customer, ManageCustomerRequest $request)
    {
        return view('backend.customer.lost')->withCustomer($customer);
    }

    /**
     * @param Customer $customer
     * @param ManageCustomerRequest $request
     * @return mixed
     */
    public function doBindCard(Customer $customer, ManageCustomerRequest $request)
    {
        $this->customerRepo->bindCard($customer, $request->all());

        return redirect()->route('admin.customer.index')->withFlashSuccess(trans('alerts.backend.customer.binded'));
    }

    /**
     * @param Customer $customer
     * @param ManageCustomerRequest $request
     * @return mixed
     */
    public function doUnbindCard(Customer $customer, ManageCustomerRequest $request)
    {
        $this->customerRepo->unbindCard($customer);

        return redirect()->route('admin.customer.index')->withFlashSuccess(trans('alerts.backend.customer.unbinded'));
    }

    /**
     * @param Customer $customer
     * @param ManageCustomerRequest $request
     * @return mixed
     */
    public function doLostCard(Customer $customer, ManageCustomerRequest $request)
    {
        $this->customerRepo->lostCard($customer, $request->all());

        return redirect()->route('admin.customer.index')->withFlashSuccess(trans('alerts.backend.customer.lost'));
    }

    /**
     * @param Customer $customer
     * @param StoreCustomerRequest $request
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function rechargeAndPay(Customer $customer, StoreCustomerRequest $request)
    {
        $input = $request->all();

        $user = Auth::User();
        $input['restaurant_id'] = $user->restaurant_id;
        $input['restaurant_user_id'] = $user->id;
        $input['customer_id'] = $customer->id;
        $input['card_id'] = $customer->card->internal_number;

        try
        {
            $rechargeOrder = $this->rechargeOrderRepo->create($input);

            if ($rechargeOrder->status != RechargeOrderStatus::COMPLETE)
            {
                $rechargeOrder = $this->rechargeOrderRepo->pay($rechargeOrder, $input['barcode']);
            }
        }
        catch (ApiException $exception)
        {
            return json_encode(['error_code'=>$exception->getCode(), 'error_message' => $exception->getMessage()]);
        }

        return json_encode([]);
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
