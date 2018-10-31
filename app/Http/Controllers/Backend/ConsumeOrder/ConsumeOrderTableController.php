<?php

namespace App\Http\Controllers\Backend\ConsumeOrder;

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
     * @return mixed
     */
    public function __invoke()
    {
        $user = Auth::User();

        return DataTables::of($this->consumeOrderRepo->getByRestaurantWithRelationQuery($user->restaurant_id))
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
