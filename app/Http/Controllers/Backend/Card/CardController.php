<?php

namespace App\Http\Controllers\Backend\Card;

use App\Exceptions\Api\ApiException;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Card\GetCardRequest;
use App\Http\Requests\Backend\Card\ManageCardRequest;
use App\Http\Requests\Backend\Card\StoreCardRequest;
use App\Modules\Enums\CardStatus;
use App\Modules\Enums\ErrorCode;
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

        return redirect()->route('admin.card.index')->withFlashSuccess(trans('alerts.backend.card.updated'));
    }

    /**
     * @param GetCardRequest $request
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function getByInternalNumber(GetCardRequest $request)
    {
        $internal_number = $request->get('internal_number');

        $restaurant_id = Auth::User()->restaurant_id;
        try
        {
            $card = $this->cardRepo->getByInternalNumber($restaurant_id, $internal_number);
        }
        catch (ApiException $exception)
        {
            return json_encode(['error_code'=>$exception->getCode(), 'error_message' => $exception->getMessage()]);
        }


        return $this->responseSuccessWithObject($card);
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