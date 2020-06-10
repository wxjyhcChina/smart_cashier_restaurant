<?php

namespace App\Http\Controllers\Backend\Materials;

use App\Exceptions\GeneralException;
use App\Http\Requests\Backend\Goods\ManageGoodsRequest;
use App\Http\Requests\Backend\Shop\ManageShopRequest;
use App\Http\Requests\Backend\Shop\StoreShopRequest;
use App\Modules\Models\Goods\Goods;
use App\Modules\Models\Shop\Shop;
use App\Repositories\Backend\Materials\MaterialsRepository;
use App\Repositories\Backend\Shop\ShopRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MaterialsController extends Controller
{
    /**
     * @var MaterialsRepository
     */
    private $materialsRepo;

    /**
     * MaterialsController constructor.
     * @param $materialsRepo
     */
    public function __construct(MaterialsRepository $materialsRepo)
    {
        $this->materialsRepo = $materialsRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @param ManageShopRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(ManageShopRequest $request)
    {
        //
        return view('backend.shop.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ManageShopRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(ManageShopRequest $request)
    {
        //
        return view('backend.shop.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws GeneralException
     */
    public function store(StoreShopRequest $request)
    {
        //
        $input = $request->all();
        $input['restaurant_id'] = Auth::User()->restaurant_id;

        $this->shopRepo->create($input);

        return redirect()->route('admin.shop.index')->withFlashSuccess(trans('alerts.backend.shop.created'));
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
     * @param  Shop $shop
     * @param  ManageShopRequest $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Shop $shop, ManageShopRequest $request)
    {
        //
        Log::info("shop:".json_encode($shop));
        return view('backend.shop.edit')->withShop($shop);
    }

    /**
     * @param Shop $shop
     * @param StoreShopRequest $request
     * @return mixed
     * @throws \App\Exceptions\Api\ApiException
     */
    public function update(Shop $shop, StoreShopRequest $request)
    {
        //
        $input = $request->all();
        $input['restaurant_id'] = Auth::User()->restaurant_id;
        if (isset($input['default']))
        {
            $input['default'] = true;
        }

        $this->shopRepo->update($shop, $input);

        return redirect()->route('admin.shop.index')->withFlashSuccess(trans('alerts.backend.shop.updated'));
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

    public function assignMaterialsCategory(Goods $goods, ManageGoodsRequest $request)
    {
        return view('backend.goods.assignLabelCategory')->withGoods($goods);
    }
}
