<?php

namespace App\Http\Controllers\Backend\Card;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Card\CardRepository;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CardTableController extends Controller
{
    /**
     * @var CardRepository
     */
    private $cardRepo;

    /**
     * CardController constructor.
     * @param $cardRepo
     */
    public function __construct(CardRepository $cardRepo)
    {
        $this->cardRepo = $cardRepo;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function __invoke()
    {
        $user = Auth::User();

        return DataTables::of($this->cardRepo->getByRestaurantQuery($user->restaurant_id))
            ->addColumn('actions', function ($card) {
                return $card->restaurant_action_buttons;
            })
            ->addColumn('show_status', function ($card){
                return $card->getShowStatusAttribute();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
