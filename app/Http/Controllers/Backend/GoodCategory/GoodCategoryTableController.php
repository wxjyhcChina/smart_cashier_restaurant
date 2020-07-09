<?php

namespace App\Http\Controllers\Backend\GoodCategory;

use App\Http\Requests\Backend\GoodCategory\ManageGoodCategoryRequest;
use App\Http\Requests\Backend\Goods\ManageGoodsRequest;
use App\Modules\Models\Goods\Goods;
use App\Modules\Models\Label\LabelCategory;
use App\Repositories\Backend\GoodCategory\GoodCategoryRepository;
use App\Repositories\Backend\Goods\GoodsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\Facades\DataTables;

class GoodCategoryTableController extends Controller
{
    /**
     * @var GoodCategoryRepository
     */
    private $goodCategoryRepo;

    /**
     * GoodsController constructor.
     * @param $goodCategoryRepo
     */
    public function __construct(GoodCategoryRepository $goodCategoryRepo)
    {
        $this->goodCategoryRepo = $goodCategoryRepo;
    }

    /**
     * @param ManageGoodsRequest $request
     * @return mixed
     */
    public function __invoke(ManageGoodCategoryRequest $request)
    {
        $user = Auth::User();

        return DataTables::of($this->goodCategoryRepo->getByShopQuery($user->shop_id))
            ->addColumn('actions', function ($goods) {
                return $goods->restaurant_action_buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

}
