<?php

namespace App\Http\Controllers\Backend\Stocks;

use App\Http\Requests\Backend\Stocks\ManageStocksRequest;
use App\Modules\Models\Stocks\StocksDetail;
use App\Repositories\Backend\Shop\ShopRepository;
use App\Repositories\Backend\Stocks\StocksRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class StocksTableController extends Controller
{
    /**
     * @var StocksRepository
     */
    private $stocksRepo;

    /**
     * StocksController constructor.
     * @param $stocksRepo
     */
    public function __construct(StocksRepository $stocksRepo)
    {
        $this->stocksRepo = $stocksRepo;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function __invoke()
    {
        $user = Auth::User();

        return DataTables::of($this->stocksRepo->getByShopQuery($user->shop_id))
            ->addColumn('actions', function ($card) {
                return $card->restaurant_action_buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function getDailyConsumeStatistics(ManageStocksRequest $request)
    {
        $user = Auth::User();
        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');

        return DataTables::of(StocksDetail::query()
            ->select('stocks_detail.*','materials.name as name')
            ->leftJoin('stocks', 'stocks.material_id', '=', 'stocks_detail.material_id')
            ->leftJoin('materials', 'materials.id', '=', 'stocks.material_id')
            ->where('stocks.shop_id',$user->shop_id)
            ->where('stocks_detail.created_at', '>=', $start_time)->where('stocks_detail.created_at', '<=', $end_time))
            ->addColumn('show_status', function ($stocksDetail) {
                return $stocksDetail->getShowStatusAttribute();
            })
            ->make(true);
    }
}
