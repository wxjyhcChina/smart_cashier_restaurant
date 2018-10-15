<?php

namespace App\Http\Controllers\Api;

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
    public function index()
    {
        //
        $user = Auth::User();

        $goods = $this->goodsRepo->getByRestaurant($user->restaurant_id);

        return $this->responseSuccess($goods);
    }

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
}
