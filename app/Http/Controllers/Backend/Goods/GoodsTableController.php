<?php

namespace App\Http\Controllers\Backend\Goods;

use App\Http\Requests\Backend\Goods\ManageGoodsRequest;
use App\Modules\Models\Goods\Goods;
use App\Modules\Models\Label\LabelCategory;
use App\Repositories\Backend\Goods\GoodsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\Facades\DataTables;

class GoodsTableController extends Controller
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
     * @param ManageGoodsRequest $request
     * @return mixed
     */
    public function __invoke(ManageGoodsRequest $request)
    {
        $user = Auth::User();

        return DataTables::of($this->goodsRepo->getByRestaurantWithRelationQuery($user->restaurant_id))
            ->addColumn('actions', function ($goods) {
                return $goods->restaurant_action_buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }


    /**
     * @param ManageGoodsRequest $request
     * @return mixed
     */
    public function getLabelCategories(ManageGoodsRequest $request)
    {
        $user = Auth::User();
        $goods_id = $request->get('goods_id');

        $query = LabelCategory::with('goods')->where('restaurant_id', $user->restaurant_id);

        return Datatables::of($query)
            ->addColumn('checked', function($labelCategory) use ($goods_id) {
                $goods = $labelCategory->goods;
                $checked = false;
                foreach ($goods as $existingGoods)
                {
                    if ($existingGoods->id == $goods_id)
                    {
                        $checked = true;
                        break;
                    }
                }

                return $checked;
            })
            ->make(true);
    }
}
