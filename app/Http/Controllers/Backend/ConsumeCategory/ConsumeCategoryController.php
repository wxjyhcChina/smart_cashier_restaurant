<?php

namespace App\Http\Controllers\Backend\ConsumeCategory;

use App\Exceptions\GeneralException;
use App\Http\Requests\Backend\ConsumeCategory\ManageConsumeCategoryRequest;
use App\Http\Requests\Backend\ConsumeCategory\StoreConsumeCategoryRequest;
use App\Modules\Models\ConsumeCategory\ConsumeCategory;
use App\Repositories\Backend\ConsumeCategory\ConsumeCategoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ConsumeCategoryController extends Controller
{
    /**
     * @var ConsumeCategoryRepository
     */
    private $consumeCategoryRepo;

    /**
     * ConsumeCategoryController constructor.
     * @param $consumeCategoryRepo
     */
    public function __construct(ConsumeCategoryRepository $consumeCategoryRepo)
    {
        $this->consumeCategoryRepo = $consumeCategoryRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @param ManageConsumeCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(ManageConsumeCategoryRequest $request)
    {
        //
        return view('backend.consumeCategory.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ManageConsumeCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(ManageConsumeCategoryRequest $request)
    {
        //
        return view('backend.consumeCategory.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreConsumeCategoryRequest  $request
     * @return \Illuminate\Http\Response
     * @throws GeneralException
     */
    public function store(StoreConsumeCategoryRequest $request)
    {
        //
        $input = $request->all();
        $input['restaurant_id'] = Auth::User()->restaurant_id;

        $this->consumeCategoryRepo->create($input);

        return redirect()->route('admin.consumeCategory.index')->withFlashSuccess(trans('alerts.backend.consumeCategory.created'));
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
     * @param  ConsumeCategory $consumeCategory
     * @param  ManageConsumeCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function edit(ConsumeCategory $consumeCategory, ManageConsumeCategoryRequest $request)
    {
        //
        return view('backend.consumeCategory.edit')->withConsumeCategory($consumeCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ConsumeCategory $consumeCategory
     * @param  StoreConsumeCategoryRequest $request
     * @return \Illuminate\Http\Response
     * @throws GeneralException
     */
    public function update(ConsumeCategory $consumeCategory, StoreConsumeCategoryRequest $request)
    {
        //
        $this->consumeCategoryRepo->update($consumeCategory, $request->all());

        return redirect()->route('admin.consumeCategory.index')->withFlashSuccess(trans('alerts.backend.consumeCategory.updated'));
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
