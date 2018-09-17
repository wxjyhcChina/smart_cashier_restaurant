<?php

namespace App\Http\Controllers\Backend\Card;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Card\CardRepository;
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


    public function __invoke()
    {
        return DataTables::of($this->cardRepo->getForDataTable())
            ->addColumn('actions', function ($card) {
                return $card->action_buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
