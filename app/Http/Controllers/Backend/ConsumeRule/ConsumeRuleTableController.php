<?php

namespace App\Http\Controllers\Backend\ConsumeRule;

use App\Repositories\Backend\ConsumeRule\ConsumeRuleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ConsumeRuleTableController extends Controller
{
    /**
     * @var ConsumeRuleRepository
     */
    private $consumeRuleRepo;

    /**
     * ConsumeRuleTableController constructor.
     * @param $consumeRuleRepo
     */
    public function __construct(ConsumeRuleRepository $consumeRuleRepo)
    {
        $this->consumeRuleRepo = $consumeRuleRepo;
    }


    public function __invoke()
    {
        $user = Auth::User();

        return DataTables::of($this->consumeRuleRepo->getByShopWithRelationQuery($user->shop_id))
            ->addColumn('actions', function ($consumeRule) {
                return $consumeRule->restaurant_action_buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
