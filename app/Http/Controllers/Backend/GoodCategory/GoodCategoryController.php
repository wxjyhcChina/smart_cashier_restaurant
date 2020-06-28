<?php

namespace App\Http\Controllers\Backend\GoodCategory;

use App\Common\Qiniu;
use App\Http\Requests\Backend\GoodCategory\ManageGoodCategoryRequest;
use App\Http\Requests\Backend\GoodCategory\StoreGoodCategoryRequest;
use App\Modules\Models\GoodCategory\GoodCategory;
use App\Modules\Models\Goods\Goods;
use App\Modules\Models\Materials\Materials;
use App\Modules\Models\Shop\Shop;
use App\Repositories\Backend\DinningTime\DinningTimeRepository;
use App\Repositories\Backend\GoodCategory\GoodCategoryRepository;
use App\Repositories\Backend\Goods\GoodsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Class GoodsController
 * @package App\Http\Controllers\Backend\Goods
 */
class GoodCategoryController extends Controller
{
    /**
     * @var GoodCategoryRepository
     */
    private $goodCategoryRepo;


    /**
     * GoodsController constructor.
     * @param GoodCategoryRepository $goodCategoryRepo
     */
    public function __construct(GoodCategoryRepository $goodCategoryRepo)
    {
        $this->goodCategoryRepo = $goodCategoryRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @param ManageGoodCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(ManageGoodCategoryRequest $request)
    {
        //
        return view('backend.goodCategory.index');
    }

    /**
     * @param $models
     * @return array
     */
    private function getSelectArray($models)
    {
        $selectArray = [null => '请选择'];
        foreach ($models as $model)
        {
            $selectArray[$model->id] = $model->name;
        }

        return $selectArray;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $user = Auth::User();
        $shops = Shop::where('id', $user->shop_id)->get();
        $shops = $this->getSelectArray($shops);

        return view('backend.goodCategory.create')
            ->withShops($shops);
    }


    /**
     * @param StoreGoodCategoryRequest $request
     * @return mixed
     * @throws \App\Exceptions\Api\ApiException
     */
    public function store(StoreGoodCategoryRequest $request)
    {
        //
        $input = $request->all();
        $input['restaurant_id'] = Auth::User()->restaurant_id;

        $this->goodCategoryRepo->create($input);

        return redirect()->route('admin.goodCategory.index')->withFlashSuccess(trans('alerts.backend.goodCategory.created'));
    }

    /**
     * @param Goods $goods
     * @param ManageGoodCategoryRequest $request
     * @return mixed
     */
    public function show(Goods $goods, ManageGoodCategoryRequest $request)
    {
        //
        return view('backend.goods.info')->withGoods($goods);
    }

    /**
     * @param GoodCategory $goodCategory
     * @param ManageGoodCategoryRequest $request
     * @return mixed
     */
    public function edit(GoodCategory $goodCategory, ManageGoodCategoryRequest $request)
    {
        //
        $user = Auth::User();
        //$shops = Shop::where('restaurant_id', $user->restaurant_id)->get();
        $shops = Shop::where('id', $user->shop_id)->get();
        $shops = $this->getSelectArray($shops);



        return view('backend.goodCategory.edit')
            ->withGoodCategory($goodCategory)
            ->withShops($shops);
    }

    /**
     * @param GoodCategory $goodCategory
     * @param StoreGoodCategoryRequest $request
     * @return mixed
     * @throws \App\Exceptions\Api\ApiException
     */
    public function update(GoodCategory $goodCategory, StoreGoodCategoryRequest $request)
    {
        //
        $this->goodCategoryRepo->update($goodCategory, $request->all());

        return redirect()->route('admin.goodCategory.index')->withFlashSuccess(trans('alerts.backend.goodCategory.updated'));
    }

    /**
     * @param GoodCategory $goodCategory
     * @param ManageGoodCategoryRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function destroy(GoodCategory $goodCategory, ManageGoodCategoryRequest $request)
    {
        //
        $this->goodCategoryRepo->delete($goodCategory);

        return redirect()->back()->withFlashSuccess(trans('alerts.backend.goodCategory.deleted'));
    }


}
