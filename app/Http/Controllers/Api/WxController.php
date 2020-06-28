<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Api\Card\CardRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class WxController extends Controller
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


    public function index(Request $request)
    {
        //
        $conditions = $request->all();
        $restaurant_id = Auth::User()->restaurant_id;
        $conditions['restaurant_id'] = $restaurant_id;

        $card = $this->cardRepo->findOne($conditions);

        return $this->responseSuccessWithObject($card);
    }

    public function test(){
        $data = ['score'=>mt_rand(40,90),'user'=>['user1','user2','user3']];
        echo json_encode($data);
    }

    public function getAllInfo(Request $request)
    {
        $restId=request('restId','');
        $phone=request('phone','');
        $userData=DB::table("customers")
            ->select("customers.*","departments.name as departments_name","consume_categories.name as consume_name","accounts.balance as balance")
            ->leftJoin("departments","customers.department_id","=","departments.id")
            ->leftJoin("accounts","accounts.customer_id","customers.id")
            ->leftJoin("consume_categories","customers.consume_category_id","=","consume_categories.id")
            ->where('customers.telephone', $phone)
            ->where("customers.restaurant_id",$restId)->get();
        $cardInfo=DB::table("cards")
            ->select("cards.*")
            ->leftJoin("customers","cards.customer_id","customers.id")
            ->where('customers.telephone', $phone)
            ->where("customers.restaurant_id",$restId)->get();
        /**
        $dinningPlace=DB::table("dinning_place")
            ->select("dinning_place.*")
            ->leftJoin("customers","dinning_place.customer_id","customers.id")
            ->where('customers.telephone', $phone)
            ->where("customers.restaurant_id",$restId)
            ->where("dinning_place.enable",1)
            ->orderBy("default","asc")
            ->get();
         * **/
        $data["userData"]=$userData;
        $data["cardInfo"]=$cardInfo;
        //$data["dinningPlace"]=$dinningPlace;
        echo json_encode($data);
    }

    /**
     * 获取该商店的用餐时间段
     * restId 餐厅id
     */
    public function getDinnerTime(){
        $restId=request('restId','');
        $shopId=$_REQUEST['shopId'];
        $data = DB::table('dinning_time')
            //->where('restaurant_id',$restId)
            ->where('shop_id',$shopId)
            ->where('enabled',1)
            ->get();
        echo $data;
    }
}
