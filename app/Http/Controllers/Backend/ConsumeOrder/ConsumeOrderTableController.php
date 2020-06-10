<?php

namespace App\Http\Controllers\Backend\ConsumeOrder;

use App\Http\Requests\Backend\ConsumeOrder\ManageConsumeOrderRequest;
use App\Modules\Enums\ConsumeOrderStatus;
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

        $start_time = '2018-01-01 00:00:00';
        $end_time = date('Y-m-d H:i:s');
        return DataTables::of(
            $this->consumeOrderRepo->getByShopWithRelationQuery($user->shop_id,
                $start_time,
                $end_time,
                null,
                null,
               null,
                [ConsumeOrderStatus::COMPLETE]
                ))
            ->addColumn('actions', function ($consumeOrder) {
                return $consumeOrder->restaurant_action_buttons;
            })
            ->addColumn('show_status', function ($consumeOrder) {
                return $consumeOrder->getShowStatusAttribute();
            })
            ->addColumn('show_pay_method', function ($consumeOrder) {
                return $consumeOrder->getShowPayMethodAttribute();
            })
            ->rawColumns(['actions', 'show_status'])
            ->make(true);
    }
}
