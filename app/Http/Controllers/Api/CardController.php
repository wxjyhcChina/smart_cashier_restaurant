<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Api\Card\CardRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\ApiException
     */
    public function index(Request $request)
    {
        //
        $conditions = $request->all();

        $card = $this->cardRepo->findOne($conditions);

        return $this->responseSuccessWithObject($card);
    }
}
