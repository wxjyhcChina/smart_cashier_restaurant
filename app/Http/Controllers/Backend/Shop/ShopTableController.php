<?php

namespace App\Http\Controllers\Backend\Shop;

use App\Repositories\Backend\Shop\ShopRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ShopTableController extends Controller
{
    /**
     * @var ShopRepository
     */
    private $shopRepo;

    /**
     * ShopController constructor.
     * @param $shopRepo
     */
    public function __construct(ShopRepository $shopRepo)
    {
        $this->shopRepo = $shopRepo;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function __invoke()
    {
        $user = Auth::User();

        return DataTables::of($this->shopRepo->getByRestaurantQuery($user->restaurant_id))
            ->addColumn('actions', function ($card) {
                return $card->restaurant_action_buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
