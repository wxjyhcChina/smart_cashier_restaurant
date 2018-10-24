<?php

namespace App\Http\Controllers\Backend\LabelCategory;

use App\Http\Requests\Backend\LabelCategory\ManageLabelCategoryRequest;
use App\Modules\Models\Label\Label;
use App\Repositories\Backend\Label\LabelCategoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\Facades\DataTables;

class LabelCategoryTableController extends Controller
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
     * @param ManageLabelCategoryRequest $request
     * @return mixed
     */
    public function __invoke(ManageLabelCategoryRequest $request)
    {
        $user = Auth::User();

        return DataTables::of($this->labelCategoryRepo->getByRestaurantQuery($user->restaurant_id))
            ->addColumn('actions', function ($labelCategory) {
                return $labelCategory->restaurant_action_buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * @param ManageLabelCategoryRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function getLabels(ManageLabelCategoryRequest $request)
    {
        $labels = Label::whereNull('label_category_id')->orWhere('label_category_id', Input::get('label_category_id'))->get();

        $labels = $labels->sortByDesc('label_category_id');

        return Datatables::of($labels)
            ->addColumn('checked', function($label) {
                $label_category_id = Input::get('label_category_id');
                $checked = $label->label_category_id == $label_category_id;

                return $checked;
            })
            ->make(true);
    }
}
