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

    public function show(Stocks $stock, ManageStocksRequest $request)
    {
        //
        return view('backend.stocks.info')->withStock($stock);
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

    public function purchase(ManageStocksRequest $request){
        return view('backend.stocks.purchase');
    }

    //采购信息
    public function purchaseInfo(Stocks $stock, ManageStocksRequest $request)
    {
        //
        //Log::info("Stocks:".json_encode($stock));
        $materials=$this->materialsRepo->getInfoById($stock->material_id)->get();
        return view('backend.stocks.info')->withStock($stock)
            ->withMaterials($materials);
    }

    //记录采购信息
    public function keepPurchase(Stocks $stock,ManageStocksRequest $request){
        //Log::info("keepPurchase:".json_encode($stock));
        $input = $request->all();
        //Log::info('keepPurchase:'.json_encode($input));
        $this->stocksRepo->purchase($stock,$input);
        return redirect()->route('admin.stocks.purchase')->withFlashSuccess(trans('alerts.backend.stocks.purchaseUpdated'));
    }

    public function stockConsume(Stocks $stock, ManageStocksRequest $request)
    {
        //
        //Log::info("Stocks:".json_encode($stock));
        $materials=$this->materialsRepo->getInfoById($stock->material_id)->get();
        return view('backend.stocks.stockConsume')->withStock($stock)
            ->withMaterials($materials);
    }

    public function keepStockConsume(Stocks $stock,ManageStocksRequest $request){
        //Log::info("keepPurchase:".json_encode($stock));
        $input = $request->all();
        //Log::info('keepPurchase:'.json_encode($input));
        $this->stocksRepo->stockConsume($stock,$input);
        return redirect()->route('admin.stocks.index')->withFlashSuccess(trans('alerts.backend.stocks.expendUpdated'));
    }

    //统计查询总览
    public function materialStatistics(ManageStocksRequest $request){
        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');
        $shop_id = $request->get('shop_id');

        $orders = $this->fetchMaterialStatistics($start_time, $end_time, $shop_id);

        return $orders;
    }

    public function fetchMaterialStatistics($start_time, $end_time, $shop_id)
    {
        if($shop_id==null){
            $shop_id= Auth::User()->shop_id;
        }

        $orders = $this->stocksRepo->getByShopWithRelationQuery($start_time, $end_time, $shop_id)->get();

        return $orders;
    }
}
