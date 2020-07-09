<?php

namespace App\Http\Controllers\Api;

use App\Common\Qiniu;
use App\Http\Requests\Api\LabelCategory\AssignLabelRequest;
use App\Http\Requests\Api\LabelCategory\StoreLabelCategoryRequest;
use App\Modules\Models\Label\LabelCategory;
use App\Repositories\Api\Label\LabelCategoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LabelCategoryController extends Controller
{
    /**
     * @var LabelCategoryRepository
     */
    private $labelCategoryRepo;

    /**
     * LabelCategoryController constructor.
     * @param $labelCategoryRepo
     */
    public function __construct(LabelCategoryRepository $labelCategoryRepo)
    {
        $this->labelCategoryRepo = $labelCategoryRepo;
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

        //$labelCategories = $this->labelCategoryRepo->getByRestaurant($user->restaurant_id, $request->all());
        $labelCategories = $this->labelCategoryRepo->getByShop($user->shop_id, $request->all());
        return $this->responseSuccess($labelCategories);
    }

    /**
     * @param LabelCategory $labelCategory
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLabels(LabelCategory $labelCategory)
    {
        return $this->responseSuccess($this->labelCategoryRepo->getLabels($labelCategory));
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @param StoreLabelCategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\ApiException
     */
    public function store(StoreLabelCategoryRequest $request)
    {
        //
        $input = $request->all();
        $input['restaurant_id'] = Auth::User()->restaurant_id;
        $input['shop_id'] = Auth::User()->shop_id;

        $labelCategory = $this->labelCategoryRepo->create($input);

        return $this->responseSuccessWithObject($labelCategory);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * @param LabelCategory $labelCategory
     * @param StoreLabelCategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\ApiException
     */
    public function update(LabelCategory $labelCategory, StoreLabelCategoryRequest $request)
    {
        //
        $labelCategory = $this->labelCategoryRepo->update($labelCategory, $request->all());

        return $this->responseSuccessWithObject($labelCategory);
    }

    /**
     * @param LabelCategory $labelCategory
     * @param AssignLabelRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function storeLabels(LabelCategory $labelCategory, AssignLabelRequest $request)
    {
        $this->labelCategoryRepo->assignLabel($labelCategory, $request->get('labels'));

        return $this->responseSuccess();
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
}
