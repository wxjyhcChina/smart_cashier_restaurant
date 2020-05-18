<?php

namespace App\Http\Controllers\Backend\PayMethod;

use App\Http\Requests\Backend\PayMethod\ManagePayMethodRequest;
use App\Repositories\Backend\PayMethod\PayMethodRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PayMethodTableController extends Controller
{
    /**
     * @var PayMethodRepository
     */
    private $payMethodRepo;

    /**
     * PayMethodController constructor.
     * @param $payMethodRepo
     */
    public function __construct(PayMethodRepository $payMethodRepo)
    {
        $this->payMethodRepo = $payMethodRepo;
    }

    /**
     * @param ManagePayMethodRequest $request
     * @return mixed
     */
    public function __invoke(ManagePayMethodRequest $request)
    {
        $user = Auth::User();

        return DataTables::of($this->payMethodRepo->getByShopQuery($user->shop_id))
            ->addColumn('actions', function ($payMethod) {
                return $payMethod->restaurant_action_buttons;
            })
            ->addColumn('show_method', function ($payMethod) {
                return $payMethod->getShowMethodName();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
