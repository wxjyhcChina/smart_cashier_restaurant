<?php

namespace App\Http\Controllers\Backend\Customer;

use App\Repositories\Backend\Card\CardRepository;
use App\Repositories\Backend\Customer\CustomerRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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

        return DataTables::of($this->customerRepo->getByRestaurantQuery($user->restaurant_id))
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
}
