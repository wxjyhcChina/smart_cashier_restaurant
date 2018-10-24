<?php

namespace App\Http\Controllers\Backend\LabelCategory;

use App\Repositories\Backend\Label\LabelCategoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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

    public function __invoke()
    {
        $user = Auth::User();

        return DataTables::of($this->labelCategoryRepo->getByRestaurantQuery($user->restaurant_id))
            ->addColumn('actions', function ($labelCategory) {
                return $labelCategory->restaurant_action_buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
