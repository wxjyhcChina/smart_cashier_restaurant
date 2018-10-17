<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\Api\ApiException;
use App\Http\Requests\Api\ConsumeRule\StoreConsumeRuleRequest;
use App\Http\Requests\Api\ConsumeRule\UpdateConsumeRuleRequest;
use App\Modules\Models\ConsumeRule\ConsumeRule;
use App\Repositories\Api\ConsumeRule\ConsumeRuleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ConsumeRuleController extends Controller
{
    /**
     * @var ConsumeRuleRepository
     */
    private $consumeRuleRepo;

    /**
     * ConsumeRuleController constructor.
     * @param $consumeRuleRepo
     */
    public function __construct(ConsumeRuleRepository $consumeRuleRepo)
    {
        $this->consumeRuleRepo = $consumeRuleRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = Auth::User();
        $consumeRules = $this->consumeRuleRepo->getByRestaurant($user->restaurant_id);

        return $this->responseSuccess($consumeRules);
    }

    /**
     * @param ConsumeRule $consumeRule
     * @return ConsumeRule
     */
    public function get(ConsumeRule $consumeRule)
    {
        return $this->consumeRuleRepo->getConsumeRuleInfo($consumeRule);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreConsumeRuleRequest  $request
     * @return \Illuminate\Http\Response
     * @throws ApiException
     */
    public function store(StoreConsumeRuleRequest $request)
    {
        //
        $input = $request->all();
        $input['restaurant_id'] = Auth::User()->restaurant_id;

        $consumeRule = $this->consumeRuleRepo->create($input);

        return $this->responseSuccessWithObject($consumeRule);
    }

    /**
     * @param ConsumeRule $consumeRule
     * @param UpdateConsumeRuleRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ApiException
     */
    public function update(ConsumeRule $consumeRule, UpdateConsumeRuleRequest $request)
    {
        //
        $input = $request->all();
        $input['restaurant_id'] = Auth::User()->restaurant_id;

        $goods = $this->consumeRuleRepo->update($consumeRule, $input);

        return $this->responseSuccessWithObject($goods);
    }

    /**
     * @param ConsumeRule $consumeRule
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(ConsumeRule $consumeRule, Request $request)
    {
        //
        $this->consumeRuleRepo->delete($consumeRule);

        return $this->responseSuccess();
    }
}
