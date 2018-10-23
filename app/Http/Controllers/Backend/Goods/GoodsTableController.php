<?php

namespace App\Http\Controllers\Backend\Goods;

use App\Http\Requests\Backend\Goods\ManageGoodsRequest;
use App\Repositories\Backend\Goods\GoodsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class GoodsTableController extends Controller
{
    /**
     * @var GoodsRepository
     */
    private $goodsRepo;

    /**
     * GoodsController constructor.
     * @param $goodsRepo
     */
    public function __construct(GoodsRepository $goodsRepo)
    {
        $this->goodsRepo = $goodsRepo;
    }

    /**
     * @param ManageGoodsRequest $request
     * @return mixed
     */
    public function __invoke(ManageGoodsRequest $request)
    {
        $user = Auth::User();

        return DataTables::of($this->goodsRepo->getByRestaurantQuery($user->restaurant_id))
            ->addColumn('actions', function ($payMethod) {
                return $payMethod->restaurant_action_buttons;
            })
            ->addColumn('show_method', function ($payMethod) {
                return $payMethod->getShowMethodName();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
