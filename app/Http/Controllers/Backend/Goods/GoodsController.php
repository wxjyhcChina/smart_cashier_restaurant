<?php

namespace App\Http\Controllers\Backend\Goods;

use App\Common\Qiniu;
use App\Http\Requests\Backend\Goods\ManageGoodsRequest;
use App\Http\Requests\Backend\Goods\StoreGoodsRequest;
use App\Modules\Models\DinningTime\DinningTime;
use App\Modules\Models\Goods\Goods;
use App\Modules\Models\Shop\Shop;
use App\Repositories\Backend\DinningTime\DinningTimeRepository;
use App\Repositories\Backend\Goods\GoodsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Class GoodsController
 * @package App\Http\Controllers\Backend\Goods
 */
class GoodsController extends Controller
{
    /**
     * @var GoodsRepository
     */
    private $goodsRepo;

    /**
     * @var DinningTimeRepository
     */
    private $dinningTimeRepo;

    /**
     * GoodsController constructor.
     * @param $goodsRepo
     * @param $dinningTimeRepo
     */
    public function __construct(GoodsRepository $goodsRepo, DinningTimeRepository $dinningTimeRepo)
    {
        $this->goodsRepo = $goodsRepo;
        $this->dinningTimeRepo = $dinningTimeRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @param ManageGoodsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(ManageGoodsRequest $request)
    {
        //
        return view('backend.goods.index');
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
        $shops = Shop::where('restaurant_id', $user->restaurant_id)->get();
        $shops = $this->getSelectArray($shops);

        $dinningTime = $this->dinningTimeRepo->getByRestaurantQuery($user->restaurant_id)->get();

        return view('backend.goods.create')
            ->withShops($shops)
            ->withDinningTime($dinningTime);
    }

    /**
     * @param ManageGoodsRequest $request
     * @return string
     */
    public function uploadImage(ManageGoodsRequest $request)
    {
        return Qiniu::fileUploadWithCorp($request->get('avatar_src'),
            $request->get('avatar_data'),
            $_FILES['avatar_file'],
            $request->get('width'),
            $request->get('height'),
            config('constants.qiniu.image_bucket'),
            config('constants.qiniu.image_bucket_url'));
    }


    /**
     * @param StoreGoodsRequest $request
     * @return mixed
     * @throws \App\Exceptions\Api\ApiException
     */
    public function store(StoreGoodsRequest $request)
    {
        //
        $input = $request->all();
        $input['restaurant_id'] = Auth::User()->restaurant_id;

        //快销品
        if (isset($input['is_temp']))
        {
            $input['is_temp'] = 2;
        }

        $this->goodsRepo->create($input);

        return redirect()->route('admin.goods.index')->withFlashSuccess(trans('alerts.backend.goods.created'));
    }

    /**
     * @param Goods $goods
     * @param ManageGoodsRequest $request
     * @return mixed
     */
    public function show(Goods $goods, ManageGoodsRequest $request)
    {
        //
        return view('backend.goods.info')->withGoods($goods);
    }

    /**
     * @param Goods $goods
     * @param ManageGoodsRequest $request
     * @return mixed
     */
    public function edit(Goods $goods, ManageGoodsRequest $request)
    {
        //
        $user = Auth::User();
        $shops = Shop::where('restaurant_id', $user->restaurant_id)->get();
        $shops = $this->getSelectArray($shops);

        $dinningTime = $this->dinningTimeRepo->getByRestaurantQuery($user->restaurant_id)->get();

        return view('backend.goods.edit')
            ->withGoods($goods)
            ->withShops($shops)
            ->withDinningTime($dinningTime);
    }

    /**
     * @param Goods $goods
     * @param StoreGoodsRequest $request
     * @return mixed
     * @throws \App\Exceptions\Api\ApiException
     */
    public function update(Goods $goods, StoreGoodsRequest $request)
    {
        //
        $this->goodsRepo->update($goods, $request->all());

        return redirect()->route('admin.goods.index')->withFlashSuccess(trans('alerts.backend.goods.updated'));
    }

    /**
     * @param Goods $goods
     * @param ManageGoodsRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function destroy(Goods $goods, ManageGoodsRequest $request)
    {
        //
        $this->goodsRepo->delete($goods);

        return redirect()->back()->withFlashSuccess(trans('alerts.backend.goods.deleted'));
    }

    /**
     * @param Goods $goods
     * @param ManageGoodsRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function assignLabelCategory(Goods $goods, ManageGoodsRequest $request)
    {
        return view('backend.goods.assignLabelCategory')->withGoods($goods);
    }

    /**
     * @param Goods $goods
     * @param ManageGoodsRequest $request
     * @return mixed
     * @throws \Throwable
     */
    public function assignLabelCategoryStore(Goods $goods, ManageGoodsRequest $request)
    {
        $input = $request->all();
        $this->goodsRepo->assignLabelCategories($goods, $input);

        return redirect()->route('admin.goods.index')->withFlashSuccess(trans('alerts.backend.goods.labelCategoryAssigned'));
    }

}
