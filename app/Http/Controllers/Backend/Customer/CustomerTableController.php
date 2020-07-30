<?php

namespace App\Http\Controllers\Backend\Customer;

use App\Http\Requests\Backend\Customer\ManageCustomerRequest;
use App\Modules\Models\Customer\Customer;
use App\Repositories\Backend\Card\CardRepository;
use App\Repositories\Backend\Customer\CustomerRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class CustomerTableController extends Controller
{
    /**
     * @var CustomerRepository
     */
    private $customerRepo;

    /**
     * @var CardRepository
     */
    private $cardRepo;

    /**
     * CustomerController constructor.
     * @param $customerRepo
     * @param $cardRepo
     */
    public function __construct(CustomerRepository $customerRepo,
                                CardRepository $cardRepo)
    {
        $this->customerRepo = $customerRepo;
        $this->cardRepo = $cardRepo;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function __invoke()
    {
        $user = Auth::User();

        //
        //return DataTables::of($this->customerRepo->getByRestaurantQuery($user->restaurant_id))
        return DataTables::of($this->customerRepo->getByShopQuery($user->shop_id))
            ->addColumn('actions', function ($customer) {
                return $customer->restaurant_action_buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function availableCard()
    {
        $user = Auth::User();

        return DataTables::of($this->cardRepo->availableCard($user->restaurant_id))
            ->make(true);
    }

    /**
     * @param Customer $customer
     * @param ManageCustomerRequest $request
     * @return mixed
     */
    public function getConsumeOrders(Customer $customer, ManageCustomerRequest $request)
    {
        return DataTables::of($this->customerRepo->getCustomerConsumeOrderQuery($customer))
            ->addColumn('actions', function ($order) {
                return $order->action_buttons;
            })
            ->addColumn('show_status', function ($consumeOrder) {
                return $consumeOrder->getShowStatusAttribute();
            })
            ->addColumn('show_pay_method', function ($consumeOrder) {
                Log::info("pay_method:".json_encode($consumeOrder->getShowPayMethodAttribute()));
                return $consumeOrder->getShowPayMethodAttribute();
            })
            ->rawColumns(['actions', 'show_status'])
            ->make(true);
    }


    /**
     * @param Customer $customer
     * @param ManageCustomerRequest $request
     * @return mixed
     */
    public function getAccountRecords(Customer $customer, ManageCustomerRequest $request)
    {
        return DataTables::of($customer->account_records()->with('consume_order')->with('recharge_order'))
            ->addColumn('show_name', function ($consumeOrder) {
                return $consumeOrder->getShowNameAttribute();
            })
            ->addColumn('show_pay_method', function ($consumeOrder) {
                return $consumeOrder->getShowPayMethodAttribute();
            })
            ->rawColumns(['show_name'])
            ->make(true);
    }
}
