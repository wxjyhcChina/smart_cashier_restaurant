<?php

namespace App\Http\Controllers\Backend\Materials;

use App\Http\Requests\Backend\Goods\ManageGoodsRequest;
use App\Modules\Models\Label\LabelCategory;
use App\Modules\Models\Materials\Materials;
use App\Repositories\Backend\Materials\MaterialsRepository;
use App\Repositories\Backend\Shop\ShopRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class MaterialsTableController extends Controller
{
    /**
     * @var MaterialsRepository
     */
    private $materialsRepo;

    /**
     * ShopController constructor.
     * @param $materialsRepo
     */
    public function __construct(MaterialsRepository $materialsRepo)
    {
        $this->materialsRepo = $materialsRepo;
    }

    /**
     * @param ManageGoodsRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function __invoke(ManageGoodsRequest $request)
    {
        $user = Auth::User();
        $goods_id = $request->get('goods_id');

        $query = Materials::with('goods')->where('shop_id', $user->shop_id);

        return  Datatables::of($query)
            ->addColumn('checked', function($materials) use ($goods_id) {
                $goods = $materials->goods;
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
            ->addColumn('number', function($materials) use ($goods_id) {
                $goods = $materials->goods;
                $number = 0;
                foreach ($goods as $existingGoods)
                {
                    if ($existingGoods->id == $goods_id)
                    {
                        $keyId=$materials->id;

                        $data=DB::table('material_goods')
                            ->where('material_id',$keyId)
                            ->where('goods_id',$goods_id)->first();
                        //Log::info("data:".json_encode($data->number));
                        $number = $data->number;
                        break;
                    }
                }
                return $number;
            })
            ->make(true);
    }

    public function getLabelCategories(ManageGoodsRequest $request)
    {}
}
