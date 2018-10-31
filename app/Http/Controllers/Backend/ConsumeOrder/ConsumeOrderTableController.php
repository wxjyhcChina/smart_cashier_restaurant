<?php

namespace App\Http\Controllers\Backend\ConsumeOrder;

use App\Http\Requests\Backend\ConsumeOrder\ManageConsumeOrderRequest;
use App\Repositories\Backend\ConsumeOrder\ConsumeOrderRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ConsumeOrderTableController extends Controller
{
    /**
     * @var ConsumeOrderRepository
     */
    private $consumeOrderRepo;

    /**
     * ConsumeOrderTableController constructor.
     * @param $consumeOrderRepo
     */
    public function __construct(ConsumeOrderRepository $consumeOrderRepo)
    {
        $this->consumeOrderRepo = $consumeOrderRepo;
    }

    /**
     * @param ManageConsumeOrderRequest $request
     * @return mixed
     */
    public function __invoke(ManageConsumeOrderRequest $request)
    {
        $user = Auth::User();

        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');
        $dinning_time_id = $request->get('dinning_time_id');
        $pay_method= $request->get('pay_method');
        $restaurant_user_id= $request->get('restaurant_user_id');

        return DataTables::of(
            $this->consumeOrderRepo->getByRestaurantWithRelationQuery($user->restaurant_id,
                $start_time,
                $end_time,
                $dinning_time_id,
                $pay_method,
                $restaurant_user_id))
            ->addColumn('actions', function ($consumeOrder) {
                return $consumeOrder->restaurant_action_buttons;
            })
            ->addColumn('show_status', function ($consumeOrder) {
                return $consumeOrder->getShowStatusAttribute();
            })
            ->rawColumns(['actions', 'show_status'])
            ->make(true);
    }
}
