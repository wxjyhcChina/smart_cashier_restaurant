<?php

namespace App\Http\Controllers\Backend\Card;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Card\ManageCardRequest;
use App\Http\Requests\Backend\Card\StoreCardRequest;
use App\Modules\Models\Card\Card;
use App\Repositories\Backend\Card\CardRepository;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class CardController extends Controller
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
     * Display a listing of the resource.
     *
     * @param ManageCardRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(ManageCardRequest $request)
    {
        //
        return view('backend.card.index');
    }

    /**
     * Show the form for creating a new resource.
     * @param ManageCardRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(ManageCardRequest $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCardRequest $request
     * @return \Illuminate\Http\Response
     * @throws GeneralException
     */
    public function store(StoreCardRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Card $card
     * @param ManageCardRequest $request
     * @return \Illuminate\Http\Response
     */
    public function show(Card $card, ManageCardRequest $request)
    {
        //
        return view('backend.card.info')->withCard($card);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Card $card
     * @param ManageCardRequest $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Card $card, ManageCardRequest $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Card $card
     * @param  StoreCardRequest $request
     * @return \Illuminate\Http\Response
     * @throws GeneralException
     */
    public function update(Card $card, StoreCardRequest $request)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}