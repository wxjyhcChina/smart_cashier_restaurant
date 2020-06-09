<?php

namespace App\Http\Controllers\Backend\Stocks;

use App\Exceptions\GeneralException;
use App\Http\Requests\Backend\Shop\ManageShopRequest;
use App\Http\Requests\Backend\Stocks\StoreStocksRequest;
use App\Http\Requests\Backend\Stocks\ManageStocksRequest;
use App\Modules\Enums\ConsumeOrderStatus;
use App\Modules\Enums\PayMethodType;
use App\Modules\Models\ConsumeOrder\ConsumeOrder;
use App\Modules\Models\Goods\Goods;
use App\Modules\Models\Shop\Shop;
use App\Modules\Models\Stocks\Stocks;
use App\Modules\Models\Stocks\StocksDetail;
use App\Repositories\Backend\Materials\MaterialsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Backend\Stocks\StocksRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StocksController extends Controller
{
    /**
     * @var StocksRepository
     */
    private $stocksRepo;

    /**
     * @var MaterialsRepository
     */
    private $materialsRepo;

    /**
     * StocksController constructor.
     * @param MaterialsRepository $materialsRepo
     * @param StocksRepository $stocksRepo
     */
    public function __construct(StocksRepository $stocksRepo,
                                MaterialsRepository $materialsRepo)
    {
        $this->stocksRepo = $stocksRepo;
        $this->materialsRepo = $materialsRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @param ManageStocksRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(ManageStocksRequest $request)
    {
        //
        return view('backend.stocks.index');
    }

    public function show($id)
    {
        //
    }

    //编辑页面
    public function edit(Stocks $stock, ManageStocksRequest $request)
    {
        //
        //$user = Auth::User();
        //Log::info('stocks:'.json_encode($stock));
        $materials=$this->materialsRepo->getInfoById($stock->material_id)->get();
        //Log::info('materials:'.json_encode($materials));
        return view('backend.stocks.edit')
            ->withStock($stock)
            ->withMaterials($materials);
    }

    //报损修改
    public function update(Stocks $stock, ManageStocksRequest $request)
    {
        //
        $input = $request->all();
        //Log::info('stock:'.json_encode($stock));
        //Log::info('input:'.json_encode($input));
        $this->stocksRepo->frmLoss($stock,$input);
        return redirect()->route('admin.stocks.index')->withFlashSuccess(trans('alerts.backend.stocks.updated'));
    }

    //新增页面
    public function create(){
        return view('backend.stocks.create');
    }


    public function store(StoreStocksRequest $request)
    {
        //
        $input = $request->all();
        $input['restaurant_id'] = Auth::User()->restaurant_id;
        $input['shop_id'] = Auth::User()->shop_id;

        $this->materialsRepo->createMaterials($input);

        return redirect()->route('admin.stocks.index')->withFlashSuccess(trans('alerts.backend.materials.created'));
    }

    public function dailyConsume(){
        $start_time = date('Y-m-d 00:00:00');
        $end_time = date('Y-m-d H:i:s');

        return view('backend.stocks.dailyConsume')
            ->withStartTime($start_time)
            ->withEndTime($end_time);
    }

    public function getDailyConsumeStatistics(Request $request)
    {
        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');

        return $this->fetchStockConsume($start_time, $end_time);
    }

    public function fetchStockConsume($start_time, $end_time)
    {
        $user = Auth::User();
        Log::info("shop_id param:".$user->shop_id);
        $detail = StocksDetail::where('created_at', '>=', $start_time)
            ->where('created_at', '<=', $end_time)
            ->where('shop_id', $user->shop_id)
            ->get();
        Log::info("detail param:".json_encode($detail));
        $stockData = [];
/**
        $shops = Shop::where('restaurant_id', $user->restaurant_id)->get();

        $shopData = [];
        foreach ($shops as $shop)
        {
            $shopGoods = Goods::where('shop_id', $shop->id)->get();
            $data = [
                'id' => $shop->id,
                'name' => $shop->name,
                'total' => 0,
                'total_count' => 0,
                'alipay' => 0,
                'alipay_count' => 0,
                'wechat' => 0,
                'wechat_count' => 0,
                'cash' => 0,
                'cash_count' => 0,
                'card' => 0,
                'card_count' => 0
            ];

            foreach ($orders as $order)
            {
                $goods = $order->goods;

                foreach ($goods as $orderGoods)
                {
                    foreach ($shopGoods as $g)
                    {
                        if ($orderGoods->id == $g->id)
                        {
                            $price = bcdiv(bcmul($orderGoods->pivot->price, $this->geteOrderDiscount($order), 2), 10, 2);

                            $data['total'] = bcadd($data['total'], $price, 2);

                            switch ($order->pay_method)
                            {
                                case PayMethodType::CASH:
                                    $data['cash'] = bcadd($data['cash'], $price, 2);
                                    break;
                                case PayMethodType::CARD:
                                    $data['card'] = bcadd($data['card'], $price, 2);
                                    break;
                                case PayMethodType::ALIPAY:
                                    $data['alipay'] = bcadd($data['alipay'], $price, 2);
                                    break;
                                case PayMethodType::WECHAT_PAY:
                                    $data['wechat'] = bcadd($data['wechat'], $price, 2);
                                    break;
                            }
                        }
                    }
                }
            }

            array_push($shopData, $data);
        }

        return $shopData;**/
        return $stockData;
    }

}
