<?php

namespace App\Http\Controllers\Api;

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
    public function index()
    {
        //
        $user = Auth::User();

        $labelCategories = $this->labelCategoryRepo->getByRestaurant($user->restaurant_id);

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
