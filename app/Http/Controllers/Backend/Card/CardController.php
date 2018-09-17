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
        return view('backend.card.create');
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
        $input = $request->all();

        $this->cardRepo->create($input);

        return redirect()->route('admin.card.index')->withFlashSuccess(trans('alerts.backend.card.created'));
    }

    /**
     * @param ManageCardRequest $request
     * @return mixed
     */
    public function import(ManageCardRequest $request)
    {
        $file = Input::file('xls');
        Excel::load($file, function($reader) {
            $cards = $reader->all();

            $this->cardRepo->createMultipleCards($cards->toArray());
        });

        return redirect()->route('admin.card.index')->withFlashSuccess(trans('alerts.backend.card.imported'));
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
        return view('backend.card.edit')->withCard($card);
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
        $this->cardRepo->update($card, $request->all());

        return redirect()->route('admin.card.index')->withFlashSuccess(trans('alerts.backend.card.created'));
    }

    /**
     * @param Card $card
     * @param $status
     * @param ManageCardRequest $request
     * @return mixed
     * @throws GeneralException
     */
    public function mark(Card $card, $status, ManageCardRequest $request)
    {
        $this->cardRepo->mark($card, $status);

        return redirect()->back()->withFlashSuccess(trans('alerts.backend.card.updated'));
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