<?php

namespace App\Http\Controllers\Backend\LabelCategory;

use App\Common\Qiniu;
use App\Http\Requests\Backend\LabelCategory\ManageLabelCategoryRequest;
use App\Http\Requests\Backend\LabelCategory\StoreLabelCategoryRequest;
use App\Modules\Models\Label\LabelCategory;
use App\Repositories\Backend\Label\LabelCategoryRepository;
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
     * @param ManageLabelCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(ManageLabelCategoryRequest $request)
    {
        //
        return view('backend.labelCategory.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ManageLabelCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(ManageLabelCategoryRequest $request)
    {
        //
        return view('backend.labelCategory.create');
    }

    /**
     * @param ManageLabelCategoryRequest $request
     * @return string
     */
    public function uploadImage(ManageLabelCategoryRequest $request)
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
     * @param StoreLabelCategoryRequest $request
     * @return mixed
     * @throws \App\Exceptions\Api\ApiException
     */
    public function store(StoreLabelCategoryRequest $request)
    {
        //
        $input = $request->all();
        $input['restaurant_id'] = Auth::User()->restaurant_id;
        $input['shop_id'] = Auth::User()->shop_id;
        $this->labelCategoryRepo->create($input);

        return redirect()->route('admin.labelCategory.index')->withFlashSuccess(trans('alerts.backend.labelCategory.created'));

    }

    /**
     * @param LabelCategory $labelCategory
     * @param ManageLabelCategoryRequest $request
     * @return mixed
     */
    public function show(LabelCategory $labelCategory, ManageLabelCategoryRequest $request)
    {
        //
        return view('backend.labelCategory.info')->withLabelCategory($labelCategory);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  LabelCategory $labelCategory
     * @param   ManageLabelCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function edit(LabelCategory $labelCategory, ManageLabelCategoryRequest $request)
    {
        //
        return view('backend.labelCategory.edit')->withLabelCategory($labelCategory);
    }

    /**
     * @param LabelCategory $labelCategory
     * @param StoreLabelCategoryRequest $request
     * @return mixed
     * @throws \App\Exceptions\Api\ApiException
     */
    public function update(LabelCategory $labelCategory, StoreLabelCategoryRequest $request)
    {
        //
        $this->labelCategoryRepo->update($labelCategory, $request->all());

        return redirect()->route('admin.labelCategory.index')->withFlashSuccess(trans('alerts.backend.labelCategory.updated'));
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

    /**
     * @param LabelCategory $labelCategory
     * @param ManageLabelCategoryRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function assignLabel(LabelCategory $labelCategory, ManageLabelCategoryRequest $request)
    {
        return view('backend.labelCategory.assignLabel')->withLabelCategory($labelCategory);
    }

    /**
     * @param LabelCategory $labelCategory
     * @param ManageLabelCategoryRequest $request
     * @return mixed
     * @throws \Throwable
     */
    public function assignLabelStore(LabelCategory $labelCategory, ManageLabelCategoryRequest $request)
    {
        $input = $request->all();

        $this->labelCategoryRepo->assignLabel($labelCategory, $input);

        return redirect()->route('admin.labelCategory.index')->withFlashSuccess(trans('alerts.backend.labelCategory.labelAssigned'));
    }

    /**
     * @param LabelCategory $labelCategory
     * @param ManageLabelCategoryRequest $request
     * @return mixed
     */
    public function goods(LabelCategory $labelCategory, ManageLabelCategoryRequest $request)
    {
        return view('backend.labelCategory.goods')->withLabelCategory($labelCategory);
    }
}
