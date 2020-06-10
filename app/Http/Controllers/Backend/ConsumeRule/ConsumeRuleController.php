<?php

namespace App\Http\Controllers\Backend\ConsumeRule;

use App\Http\Requests\Backend\ConsumeRule\ManageConsumeRuleRequest;
use App\Http\Requests\Backend\ConsumeRule\StoreConsumeRuleRequest;
use App\Modules\Models\ConsumeRule\ConsumeRule;
use App\Repositories\Backend\ConsumeCategory\ConsumeCategoryRepository;
use App\Repositories\Backend\ConsumeRule\ConsumeRuleRepository;
use App\Repositories\Backend\DinningTime\DinningTimeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ConsumeRuleController extends Controller
{
    /**
     * @var ConsumeRuleRepository
     */
    private $consumeRuleRepo;


    /**
     * @var DinningTimeRepository
     */
    private $dinningTimeRepo;


    /**
     * @var ConsumeCategoryRepository
     */
    private $consumeCategoryRepo;

    /**
     * ConsumeRuleController constructor.
     * @param $consumeRuleRepo
     * @param $dinningTimeRepo
     * @param $consumeCategoryRepo
     */
    public function __construct(ConsumeRuleRepository $consumeRuleRepo,
                                DinningTimeRepository $dinningTimeRepo,
                                ConsumeCategoryRepository $consumeCategoryRepo)
    {
        $this->consumeRuleRepo = $consumeRuleRepo;
        $this->dinningTimeRepo = $dinningTimeRepo;
        $this->consumeCategoryRepo = $consumeCategoryRepo;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('backend.consumeRule.index');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function getWeekDays()
    {
        $collection = collect(
            [
                ['id'=>0, 'name'=>'周日'],
                ['id'=>1, 'name'=>'周一'],
                ['id'=>2, 'name'=>'周二'],
                ['id'=>3, 'name'=>'周三'],
                ['id'=>4, 'name'=>'周四'],
                ['id'=>5, 'name'=>'周五'],
                ['id'=>6, 'name'=>'周六']
            ]
        );
        Log::info("$collection:".$collection);
        return $collection;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $user = Auth::User();
        //$dinningTime = $this->dinningTimeRepo->getByRestaurantQuery($user->restaurant_id)->get();
        $dinningTime = $this->dinningTimeRepo->getByShopQuery($user->shop_id)->get();
        //$consumeCategories = $this->consumeCategoryRepo->getByRestaurantQuery($user->restaurant_id)->get();
        $consumeCategories = $this->consumeCategoryRepo->getByShopQuery($user->shop_id)->get();
        $weekdays = $this->getWeekDays();

        return view('backend.consumeRule.create')
            ->withDinningTime($dinningTime)
            ->withConsumeCategories($consumeCategories)
            ->withWeekdays($weekdays);
    }

    /**
     * @param StoreConsumeRuleRequest $request
     * @return mixed
     * @throws \App\Exceptions\Api\ApiException
     */
    public function store(StoreConsumeRuleRequest $request)
    {
        //
        $user = Auth::User();
        $input = $request->all();
        $input['restaurant_id'] = $user->restaurant_id;
        $input['shop_id'] = $user->shop_id;

        $this->consumeRuleRepo->create($input);

        return redirect()->route('admin.consumeRule.index')->withFlashSuccess(trans('alerts.backend.consumeRule.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ConsumeRule $consumeRule
     * @param ManageConsumeRuleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function edit(ConsumeRule $consumeRule, ManageConsumeRuleRequest $request)
    {
        //
        $user = Auth::User();
        //$dinningTime = $this->dinningTimeRepo->getByRestaurantQuery($user->restaurant_id)->get();
        $dinningTime = $this->dinningTimeRepo->getByShopQuery($user->shop_id)->get();
        //$consumeCategories = $this->consumeCategoryRepo->getByRestaurantQuery($user->restaurant_id)->get();
        $consumeCategories = $this->consumeCategoryRepo->getByShopQuery($user->shop_id)->get();
        $weekdays = $this->getWeekDays();

        return view('backend.consumeRule.edit')
            ->withConsumeRule($consumeRule)
            ->withDinningTime($dinningTime)
            ->withConsumeCategories($consumeCategories)
            ->withWeekdays($weekdays);
    }

    /**
     * @param ConsumeRule $consumeRule
     * @param StoreConsumeRuleRequest $request
     * @return mixed
     * @throws \App\Exceptions\Api\ApiException
     */
    public function update(ConsumeRule $consumeRule, StoreConsumeRuleRequest $request)
    {
        $user = Auth::User();
        $input = $request->all();
        $input['restaurant_id'] = $user->restaurant_id;
        $input['shop_id'] = $user->shop_id;
        //
        $this->consumeRuleRepo->update($consumeRule, $input);

        return redirect()->route('admin.consumeRule.index')->withFlashSuccess(trans('alerts.backend.consumeRule.updated'));
    }


    /**
     * @param ConsumeRule $consumeRule
     * @param $status
     * @param ManageConsumeRuleRequest $request
     * @return mixed
     * @throws \App\Exceptions\Api\ApiException
     */
    public function mark(ConsumeRule $consumeRule, $status, ManageConsumeRuleRequest $request)
    {
        //
        $this->consumeRuleRepo->mark($consumeRule, $status);

        return redirect()->back()->withFlashSuccess(trans('alerts.backend.consumeRule.updated'));
    }


    /**
     * @param ConsumeRule $consumeRule
     * @param ManageConsumeRuleRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function destroy(ConsumeRule $consumeRule, ManageConsumeRuleRequest $request)
    {
        //
        $this->consumeRuleRepo->delete($consumeRule);

        return redirect()->back()->withFlashSuccess(trans('alerts.backend.consumeRule.deleted'));
    }
}
