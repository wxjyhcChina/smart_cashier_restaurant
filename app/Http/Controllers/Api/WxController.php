<?php

namespace App\Http\Controllers\Api;

use App\Modules\Enums\ConsumeOrderStatus;
use App\Modules\Models\ConsumeOrder\ConsumeOrder;
use App\Modules\Models\Customer\Customer;
use App\Repositories\Api\Card\CardRepository;
use App\Repositories\Api\ConsumeOrder\ConsumeOrderRepository;
use App\Repositories\Backend\Customer\CustomerRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use DB;
use Iwanli\Wxxcx\Wxxcx;

class WxController extends Controller
{
    /**
     * @var ConsumeOrderRepository
     */
    private $consumeOrderRepo;

    private $customerRepo;

    /**
     * ConsumeOrderController constructor.
     * @param $consumeOrderRepo
     * @param CustomerRepository $customerRepo
     */
    public function __construct(CustomerRepository $customerRepo,ConsumeOrderRepository $consumeOrderRepo)
    {
        $this->customerRepo = $customerRepo;
        $this->consumeOrderRepo = $consumeOrderRepo;
    }

    public function index(Request $request)
    {

    }

    public function test(){
        $data = ['score'=>mt_rand(40,90),'user'=>['user1','user2','user3']];
        Log::info("test");
        echo json_encode($data);
    }

    /**
     * 获取微信用户信息
     * @return string
     * @throws \Exception
     */
    public function getWxUserInfo(){
        Log::info('WxController getWxUserInfo() arrived.');
        //code 在小程序端使用wx.login 获取
        $code=request('code','');
        //$code=str_replace(" ","+",$code);
        //Log::info($code);
        //encryptedData 和iv 在小程序段使用wx.getUserInfo获取
        $encryptedData=request('encryptedData','');
        //$encryptedData=str_replace(" ","+",$encryptedData);
        $iv=request('iv','');
        //$iv=str_replace(" ","+",$iv);
        //根据code获取用户session_key等信息，返回用户openid 和 session_key
        $wxInfo=new Wxxcx('wx7f0a0b52520c1c68','a5aa218b35d2ed0c212ef420ddd06e5e');
        $userInfo = $wxInfo->getLoginInfo($code);
        Log::info("info:".json_encode($userInfo));
        //return $userInfo;
        //获取解密后的用户信息
        return $wxInfo->getUserInfo($encryptedData,$iv);
    }

    /**
     * 获取登录人员信息
     * @param Request $request
     */
    public function getAllInfo(Request $request)
    {
        $shopId=request('shopId','');
        //Log::info($shopId);
        $phone=request('phone','');
        //Log::info($phone);
        $userData=DB::table("customers")
            ->select("customers.*","departments.name as departments_name","consume_categories.name as consume_name","accounts.balance as balance")
            ->leftJoin("departments","customers.department_id","=","departments.id")
            ->leftJoin("accounts","accounts.customer_id","customers.id")
            ->leftJoin("consume_categories","customers.consume_category_id","=","consume_categories.id")
            ->where('customers.telephone', $phone)
            ->where("customers.shop_id",$shopId)->get();
        $cardInfo=DB::table("cards")
            ->select("cards.*")
            ->leftJoin("customers","cards.customer_id","customers.id")
            ->where('customers.telephone', $phone)
            ->where("customers.shop_id",$shopId)->get();
        $data["userData"]=$userData;
        $data["cardInfo"]=$cardInfo;
        //Log::info("getAllInfo:".json_encode($data));
        echo json_encode($data);
    }

    /**
     * 获取该商店的用餐时间段
     * restId 餐厅id
     */
    public function getDinnerTime(Request $request){
        $shopId=request('shopId','');
        //Log::info($shopId);
        $data = DB::table('dinning_time')
            //->where('restaurant_id',$shopId)
            ->where('shop_id',$shopId)
            ->where('enabled',1)
            ->get();
        //Log::info("getDinnerTime:".json_encode($data));
        echo $data;
    }

    /**
     * 获取菜单
     * @param Request $request
     */
    public function getMenu(Request $request){
        $shopId=request('shopId','');
        //Log::info($shopId);
        $dinningTimeId=request('timeRanage','');
        //Log::info($dinningTimeId);
        $goodCategories=DB::table("good_categories")
            ->where('shop_id','=',$shopId)
            ->get();
        $data=['code'=>0];
        //Log::info("getMenu goodCategories:".json_encode($goodCategories));
        if($goodCategories ->first() == null){
            $data=['code'=>1];
            echo json_encode($data);
            //Log::info("getMenu d:".json_encode($data));
            return;
        }
        $foodList=array();
        $list=array();
        foreach($goodCategories as $goodCategory){
            //Log::info("getMenu goodCategory:".json_encode($goodCategory));
            $goodCategoryId=$goodCategory->id;
            //Log::info("getMenu good_category_id:".json_encode($goodCategoryId));
            $goods=DB::table('goods')
                ->select("goods.*")
                ->leftJoin("goods_dinning_time","goods.id","=","goods_dinning_time.goods_id")
                ->where('goods.shop_id','=',$shopId)
                ->where('goods_dinning_time.dinning_time_id','=',$dinningTimeId)
                ->where('goods.good_category_id','=',$goodCategoryId)
                ->get();
            //Log::info("getMenu goods:".json_encode($goods));
            $foodList=[
                'name'=>$goodCategory->name,
                'id'=>$goodCategoryId,
                'length'=>$goods->count(),
                'data'=>$goods
            ];
            array_push($list,$foodList);
            //Log::info("getMenu foodList:".json_encode($foodList));
            //Log::info("getMenu List:".json_encode($list));
        }
        $data +=['foodList'=>$list];
        //Log::info("getMenu data:".json_encode($data));
        echo json_encode($data);
    }


    //支付模块
    public function payCallback(Request $request){
        Log::info("WxController payCallback() arrived");
        $input = $request->all();
        Log::info("info:".json_encode($input));
        //完成支付
        $xml = file_get_contents("php://input");

        //xml数据转数组
        $data = $this->xmlToArray($xml);
        //保存微信服务器返回的签名sign
        $data_sign = $data['sign'];

        //sign不参与签名算法
        unset($data['sign']);
        $sign = $this->sign($data);

        //判断签名是否正确,判断支付状态
        if (($sign===$data_sign) && ($data['return_code'] == 'SUCCESS') && ($data['result_code'] == 'SUCCESS')) {
            $results = $data;
            //获取服务器返回的数据
            $order_sn = $data['out_trade_no'];	//订单号
            $order_id = $data['attach'];		//附加参数,选择传递订单ID
            $openid = $data['openid'];			//付款人openID
            $total_fee = $data['total_fee'];	//付款金额
            Log::info("data param:".json_encode($data));
            //更新状态,完成订单
            //$this->updatePsDB($order_sn,$order_id,$openid,$total_fee);
        } else {
            $results = false;
        }

        //返回状态给微信服务器
        if ($results) {
            $str = '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
        } else {
            $str = '<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[签名失败]]></return_msg></xml>';
        }
        echo $str;
        return $results;

    }

    /**
     * 卡支付前获取orderid
     * @param Request $request
     */
    public function pay(Request $request){
        $input = $request->all();
        $restaurant_id=$input['restaurantId'];
        $shop_id=$input['shopId'];
        $temp_goods=$input['tempGoods'];
        $temp_goods = explode(",", $temp_goods);
        $response=$this->preCreate($restaurant_id,$shop_id,$temp_goods);
        Log::info("pay:".json_encode($response));
        return $this->responseSuccess($response);
    }

    /**
     * 卡支付
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\ApiException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function payWithCard(Request $request)
    {
        $input = $request->all();
        $consumeOrder=ConsumeOrder::query()
            ->where('id',$input['orderId'])->first();

        $consumeOrder = $this->consumeOrderRepo->pay($consumeOrder, $input);
        Log::info("payWithCard:".json_encode($consumeOrder));
        return $this->responseSuccess($consumeOrder);
    }

    /**
     * 我的订单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function accountRecords(Request $request){
        Log::info("accountRecords");
        $input = $request->all();
        /*$customer=Customer::query()
            ->where("id",$input['userId'])->first();

        $records = $this->customerRepo->getCustomerConsumeOrderQuery($customer);*/
        $consumeOrder=ConsumeOrder::query()
            ->where('customer_id',$input['userId'])
            //->where('status', ConsumeOrderStatus::COMPLETE)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        foreach ($consumeOrder as $order){
            $order->goods;
        }
        if($consumeOrder->first() != null){
            $records=['code'=>0];
            $records +=['orderList'=>$consumeOrder];
        }else{
            $records=['code'=>1];
        }

        return $this->responseSuccessWithObject($records);
    }

    /**
     * 微信小程序预支付
     * @param  Request $request
     * @return $data
     */
    public function prepay(Request $request){
        $input = $request->all();
        Log::info("WxController pay() arrived:".json_encode($input));
        $restaurant_id=$input['restaurantId'];
        $shop_id=$input['shopId'];
        $temp_goods=$input['tempGoods'];
        $temp_goods = explode(",", $temp_goods);
        $db=DB::table("pay_methods")
            ->where("shop_id",$shop_id)
            ->where("enabled",1)
            ->where('method','WECHAT_PAY')
            ->first();
        $wechatPay=DB::table("wechat_pay_detail")
            ->where("pay_method_id",$db->id)
            ->first();
        $appid=$wechatPay->app_id;
        $mch_id=$wechatPay->mch_id;
        Log::info("prepay openid:".json_encode($appid));
        Log::info("prepay openid:".json_encode($mch_id));

        //写入

        $res=$this->preCreate($restaurant_id,$shop_id,$temp_goods);

        $appid="wx7f0a0b52520c1c68";//appid.如果是公众号 就是公众号的appid
        $body="test";
        $mch_id="1549500931";//商户号
        $secret="a5aa218b35d2ed0c212ef420ddd06e5e";
        if($input['code']){
            $code=$input['code'];
            $openid=$this->getOpenId($code,$appid,$secret);
        }
        //Log::info("prepay openid:".json_encode($openid));
        //print_r($openid);die;
        //$openid = 'ossyJ5TjaHcDq9tAVVh90a07F0QM';
        $fee =$res[price];//举例支付0.01
        $nonce_str=$this->nonce_str();//随机字符串
        $notify_url='https://www.jyjiesuan.com/api/v1/wx/payCallback';//回调的url
        $openid=$openid;
        $out_trade_no=$this->order_number($openid);
        $spbill_create_ip = $this->get_client_ip();//'127.0.0.1';
        //Log::info("spbill_create_ip:".$spbill_create_ip);
        $total_fee=$fee*100;// 微信支付单位是分，所以这里需要*100
        $trade_type='JSAPI';//交易类型 默认

        //这里是按照顺序的 因为下面的签名是按照顺序 排序错误 肯定出错
        $post['appid'] = $appid;
        $post['body'] = $body;
        $post['mch_id'] = $mch_id;
        $post['nonce_str'] = $nonce_str;//随机字符串
        $post['notify_url'] = $notify_url;
        $post['openid'] = $openid;
        $post['out_trade_no'] = $out_trade_no;
        $post['spbill_create_ip'] = $spbill_create_ip;//终端的ip
        $post['total_fee'] = $total_fee;//总金额
        $post['trade_type'] = $trade_type;
        $sign = $this->sign($post);//签名
        Log::info("sign:".$sign);
        $post_xml = '<xml>
                       <appid>'.$appid.'</appid>
                       <body>'.$body.'</body>
                       <mch_id>'.$mch_id.'</mch_id>
                       <nonce_str>'.$nonce_str.'</nonce_str>
                       <notify_url>'.$notify_url.'</notify_url>
                       <openid>'.$openid.'</openid>
                       <out_trade_no>'.$out_trade_no.'</out_trade_no>
                       <spbill_create_ip>'.$spbill_create_ip.'</spbill_create_ip>
                       <total_fee>'.$total_fee.'</total_fee>
                       <trade_type>'.$trade_type.'</trade_type>
                       <sign>'.$sign.'</sign>
                        
                    </xml> ';
        //Log::info($post_xml);
        //print_r($post_xml);
        //统一接口prepay_id
        $url="https://api.mch.weixin.qq.com/pay/unifiedorder";
        $xml=$this->http_request($url,$post_xml);

        //全大写
        $array=$this->xmlToArray($xml);

        //print_r($array);die;

        if($array['return_code']=='SUCCESS'&&$array['return_code']=='SUCCESS'){
            //::info("RETURN_CODE success");
            $time=time();
            $tmp=[];//临时数组用于签名
            $tmp['appId']=$appid;
            $tmp['nonceStr'] = $nonce_str;
            $tmp['package']='prepay_id='.$array['prepay_id'];
            $tmp['signType']='MD5';
            $tmp['timeStamp']="$time";

            $data['state']=200;
            $data['timeStamp']="$time";
            $data['nonceStr']=$nonce_str;
            $data['signType']='MD5';
            $data['package']='prepay_id='.$array['prepay_id'];
            $data['paySign'] = $this->sign($tmp);
            $data['out_trade_no'] = $out_trade_no;
        }else{
            $data['state'] = 0;
            $data['text'] = "错误";
            $data['RETURN_CODE'] = $array['RETURN_CODE'];
            $data['RETURN_MSG'] = $array['RETURN_MSG'];
        }


        echo json_encode($data);

    }


    /**
     * 获取营养信息
     * @param Request $request
     */
    public  function getGoodInfo(Request $request){
        $input = $request->all();
        $goodId=$input['goodId'];
        $materials=DB::table("materials")
                ->select("materials.name as name","material_goods.number as number")
                ->leftJoin("material_goods","material_goods.material_id","=","materials.id")
                ->where("material_goods.goods_id","=",$goodId)->get();
        Log::info("materials:".json_encode($materials));
        $nengliang=0;//能量(千卡)
        $danbai=0.00;//蛋白质(克)
        $zhifang=0.00;//脂肪(克)
        $tanshui=0.00;//碳水化合物(克)
        foreach ($materials as $material){
            $data=DB::table("yk_foodnutrition_copy1")
                ->select("yk_foodnutrition_copy1.nengliang as nengliang","yk_foodnutrition_copy1.danbai as danbai","yk_foodnutrition_copy1.zhifang as zhifang","yk_foodnutrition_copy1.tanshui as tanshui")
                ->where('name','=',$material->name)
                ->orWhere('name', 'like', '%'.$material->name.'%')
                ->first();
            Log::info("data:".json_encode($data));
            if($data != null){
                $perCount=($material->number)/100;
                $nengliang +=intval(($data->nengliang)*$perCount);
                $danbai +=intval(($data->danbai)*$perCount);
                $zhifang +=intval(($data->zhifang)*$perCount);
                $tanshui += intval(($data->tanshui)*$perCount);
            }
        }
        $info["nengliang"]=$nengliang;
        $info["danbai"]=$danbai;
        $info["zhifang"]=$zhifang;
        $info["tanshui"]=$tanshui;
        $res['materials']=$materials;
        $res['info']=$info;
        return $this->responseSuccess($res);
    }


    private function order_number($openid){
        return md5($openid.time().rand(10,99));//32
    }

    private function nonce_str(){
        $result='';
        $str='QWERTYUIOPASDFGHJKLZXVBNMqwertyuioplkjhgfdsamnbvcxz';
        for($i=0;$i<32;$i++){
            $result .= $str[rand(0,48)];
        }
        return $result;
    }
    /**
     * 通过code 获取 openid
     */
    private function getOpenId($code,$appid,$secret){
        //获得appid和secret

        $url="https://api.weixin.qq.com/sns/jscode2session?appid=$appid&secret=$secret&js_code=$code&grant_type=authorization_code";
        //Log::info($url);
        $weixin=file_get_contents($url);//通过code换取网页授权access_token
        $jsondecode=json_decode($weixin);//对json格式的字符串进行编码
        $array=get_object_vars($jsondecode);//转换成数组
        if(!isset($array['openid'])){
            throw new \Exception("code错误");
        }
        $openid=$array['openid'];
        return $openid;

    }

    /**
     * 生成签名
     * @param $data
     * @return string
     */
    private function sign($data){
        //获取微信支付秘钥
        //Log::info("sign");
        $key="0814188D2E8EEE8A2988203013730909";//申请支付后有给予一个商户账号和密码，登陆后自己设置的key

        //去空
        $data = array_filter($data);

        //签名步骤一：按字典序排序参数
        ksort($data);
        $string_a = http_build_query($data);
        $string_a = urldecode($string_a);

        //签名步骤二：在string后加入KEY
        $string_sign_temp = $string_a . "&key=$key";
        //Log::info($string_sign_temp);
        //签名步骤三：MD5加密
        $sign = md5($string_sign_temp);


        //签名步骤四：所有字符转为大写
        $result = strtoupper($sign);
        return $result;
    }

    public function http_request($url,$data = null,$headers=array()){
        $curl = curl_init();
        if( count($headers) >= 1 ){
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($curl, CURLOPT_URL, $url);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }


    //获取xml，将XML转为array
    private function xmlToArray($xml){
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $result = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $result;
    }

    //生成订单
    private function preCreate($restaurant_id,$shop_id,$temp_goods){
        //restaurant_id
        $input['restaurant_id'] =$restaurant_id;
        //discount无
        //shop_id
        $input['shop_id'] =$shop_id;
        //temp_goods
        $input['temp_goods'] =$temp_goods;

        $response = $this->consumeOrderRepo->create($input);
        Log::info(json_encode($response));
        return $response;
    }

    //更新订单状态
    private function  updatePsDB($order_sn,$order_id,$openid,$total_fee){
        Log::info("更新订单状态:".$order_id);
    }

    /**
     * 获取用户真实的IP地址
     * @return array|false|mixed|string
     */
    private function get_client_ip(){
        $cip = "unknown";
        if($_SERVER['REMOTE_ADDR']){
            $cip = $_SERVER['REMOTE_ADDR'];
        }elseif(getenv("REMOTE_ADDR")){
            $cip = getenv("REMOTE_ADDR");
        }
        return $cip;
}
}
