<?php

namespace App\Http\Controllers\Api;

use App\Common\Qiniu;
use App\Http\Requests\Api\Goods\BindLabelCategoryRequest;
use App\Http\Requests\Api\Goods\StoreGoodsRequest;
use App\Modules\Models\Goods\Goods;
use App\Repositories\Api\Goods\GoodsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GoodsController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user = Auth::User();

        $goods = $this->goodsRepo->getByRestaurant($user->restaurant_id, $request->all());

        return $this->responseSuccess($goods);
    }

    //TODO:policy
    /**
     * @param Goods $goods
     * @return Goods
     */
    public function get(Goods $goods)
    {
        return $this->goodsRepo->getGoodsInfo($goods);
    }

    /**
     * @param StoreGoodsRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\ApiException
     */
    public function store(StoreGoodsRequest $request)
    {
        //
        $input = $request->all();

        $input['restaurant_id'] = Auth::User()->restaurant_id;

        $goods = $this->goodsRepo->create($input);

        return $this->responseSuccessWithObject($goods);
    }

    /**
     * @param Goods $goods
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLabelCategories(Goods $goods)
    {
        $labelCategories = $this->goodsRepo->getLabelCategories($goods);

        return $this->responseSuccess($labelCategories);
    }

    /**
     * @param Goods $goods
     * @param BindLabelCategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\ApiException
     */
    public function storeLabelCategory(Goods $goods, BindLabelCategoryRequest $request)
    {
        $label = $request->get('label');
        $overwrite = $request->get('overwrite');

        $labelCategory = $this->goodsRepo->storeLabelCategory($goods, $label, $overwrite);

        return $this->responseSuccessWithObject($labelCategory);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function imageUploadToken()
    {
        $response = Qiniu::getUpToken(config('constants.qiniu.image_bucket'), 'png', array());

        return $this->responseSuccess(['token'=>$response]);
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
     * @param Goods $goods
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\ApiException
     */
    public function update(Goods $goods, Request $request)
    {
        //
        $goods = $this->goodsRepo->update($goods, $request->all());

        return $this->responseSuccessWithObject($goods);
    }

    /**
     * @param Goods $goods
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Goods $goods, Request $request)
    {
        //
        $this->goodsRepo->delete($goods);

        return $this->responseSuccess();
    }

    public function tableFoods(Request $request)
    {
        //
        $user = Auth::User();

        $goods = $this->goodsRepo->getTableByRestaurant($user->restaurant_id, $request->all());

        return $this->responseSuccess($goods);
    }
}
