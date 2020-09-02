<?php

namespace App\Modules\Repositories\Customer;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\CardStatus;
use App\Modules\Enums\ErrorCode;
use App\Modules\Enums\PayMethodType;
use App\Modules\Models\Card\Card;
use App\Modules\Models\Customer\Account;
use App\Modules\Models\Customer\Customer;
use App\Modules\Models\PayMethod\PayMethod;
use App\Modules\Models\Shop\Shop;
use App\Modules\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GuzzleHttp;
/**
 * Class BaseCustomerRepository.
 */
class BaseCustomerRepository extends BaseRepository
{

    /**
     * Associated Repository Model.
     */
    const MODEL = Customer::class;

    /**
     * @param $input
     * @return Customer
     * @throws ApiException
     */
    public function create($input)
    {
        $customer = $this->createCustomerStub($input);

        try
        {
            DB::beginTransaction();

            if ($customer->save())
            {
                $card = Card::where('internal_number', $input['card_id'])->first();
                if ($card == null)
                {
                    throw new ApiException(ErrorCode::RESOURCE_NOT_FOUND, trans('api.error.card_not_exist'));
                }
                else if($card->status != CardStatus::UNACTIVATED)
                {
                    throw new ApiException(ErrorCode::CARD_STATUS_INCORRECT, trans('api.error.card_status_incorrect'));
                }

                $card->customer_id = $customer->id;
                $card->status = CardStatus::ACTIVATED;
                $card->save();

                $this->createAccount($customer->id, isset($input['balance']) ? $input['balance'] : 0);
                //Log::info("分店111");
                //如果有uface信息
                //$shop=Shop::where("id",$customer->shop_id)->first();
                //Log::info("分店:".json_encode($shop));
                $method = PayMethod::where('method', PayMethodType::FACE)
                    ->where('shop_id', $customer->shop_id)
                    ->where('enabled',1)//使用中
                    ->first();
                //Log::info("分店:".json_encode($shop));
                if($method!=null){
                    //Log::info("分店2222");
                    //Log::info("分店:".json_encode($shop));
                    $shop=Shop::where("id",$customer->shop_id)->first();
                    $online_flag=$shop->face_flag;
                    $http = new GuzzleHttp\Client;
                    $faceMaven=env('JAVA_FACE_MAVEN');
                    $devices=DB::table("outer_devices")
                        ->where('shop_id',$customer->shop_id)
                        ->where('type','人脸机')
                        ->where('sources','uface')
                        ->where('enabled',1)
                        ->get();
                    $flag=true;
                    $msg="";
                    //Log::info("分店333");
                    try {
                        foreach($devices as $device){
                            Log::info("分店:".json_encode($device));
                            if($online_flag==1){
                                //宇泛在线
                                Log::info("online");
                                $response = $http->get($faceMaven.'/onlineUser/create', [
                                    'query' => [
                                        'id'=>$customer->id,
                                        'name'=>$customer->user_name,
                                        'cardNum'=>$card->internal_number,
                                        'idCardNo'=>$customer->id_license,
                                        'phone'=>$customer->telephone,
                                        'deviceKey' => $device->deviceKey,
                                        'appKey' => $shop->appKey,
                                        'appSecret'=>$shop->appSecret,
                                        'appId'=>$shop->appId,
                                        'personsetGuid'=>$shop->personsetGuid,
                                    ],
                                ]);
                            }else{
                                $ip=$device->url;
                                Log::info("create发送的外网ip:".$ip);
                                $response = $http->get($faceMaven.'/person/create', [
                                    'query' => [
                                        'ip' => $ip,
                                        'pass' => 'admin123',
                                        'id'=>$customer->id,
                                        'name'=>$customer->user_name,
                                        'idcardNum'=>$card->internal_number,
                                    ],
                                ]);
                            }
                            $res = json_decode($response->getBody(), true);
                            //log::info("res:".json_encode($res));
                            $result=$res["success"];
                            if($result==0){
                                $flag=false;
                                $msg=$res["data"];
                                break;
                            }else{
                                if($online_flag==1){
                                    $customer->personGuid=$res["guid"];
                                    $customer->save();
                                }
                            }
                        }
                    }catch (\Throwable $throwable){
                        if ($throwable instanceof ClientException) {
                            //doing something
                            throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.customer.net_error'));
                        }
                        if ($throwable instanceof ServerException) {
                            //doing something
                            throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.customer.net_error'));
                        }
                        throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.customer.net_error'));
                    }
                    /**
                    if($online_flag==1){
                        //宇泛在线
                        Log::info("online");
                    }else{
                        //if($shop!=null&&($shop->face_flag!=0)){
                        //Log::info("外接设备:".json_encode($devices));
                        try {
                            foreach($devices as $device){
                                $ip=$device->url;
                                Log::info("create发送的外网ip:".$ip);
                                $response = $http->get($faceMaven.'/person/create', [
                                    'query' => [
                                        'ip' => $ip,
                                        'pass' => 'admin123',
                                        'id'=>$customer->id,
                                        'name'=>$customer->user_name,
                                        'idcardNum'=>$card->internal_number,
                                    ],
                                ]);
                                $res = json_decode( $response->getBody(), true);
                                //log::info("res:".json_encode($res));
                                $result=$res["success"];
                                if($result==0){
                                    $flag=false;
                                    $msg=$res["data"];
                                }
                            }
                        }catch (\Throwable $throwable){
                            if ($throwable instanceof ClientException) {
                                //doing something
                                throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.customer.net_error'));
                            }
                            if ($throwable instanceof ServerException) {
                                //doing something
                                throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.customer.net_error'));
                            }
                            throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.customer.net_error'));
                        }
                    }**/
                    if($flag){
                        DB::commit();
                    }else{
                        DB::rollBack();
                        throw new ApiException(ErrorCode::DATABASE_ERROR, $msg);
                    }
                }else{
                    DB::commit();
                }
            }

        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            if ($exception instanceof ApiException)
            {
                throw $exception;
            }

            throw $exception;

            throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.customer.create_error'));
        }

        return $customer;
    }

    /**
     * @param Customer $customer
     * @param $input
     * @return Customer
     * @throws ApiException
     */
    public function update(Customer $customer, $input)
    {
        Log::info("customer update param:".json_encode($input));

        try
        {
            DB::beginTransaction();
            $customer->update($input);

            //如果有uface信息
            //$shop=Shop::where("id",$customer->shop_id)->first();
            //Log::info("分店:".json_encode($shop));
            $method = PayMethod::where('method', PayMethodType::FACE)
                ->where('shop_id', $customer->shop_id)
                ->where('enabled',1)//使用中
                ->first();
            //Log::info("分店:".json_encode($shop));
            if($method!=null){
                $shop=Shop::where("id",$customer->shop_id)->first();
                $online_flag=$shop->face_flag;
                $http = new GuzzleHttp\Client;
                $faceMaven=env('JAVA_FACE_MAVEN');
                $devices=DB::table("outer_devices")
                    ->where('shop_id',$customer->shop_id)
                    ->where('type','人脸机')
                    ->where('sources','uface')
                    ->where('enabled',1)
                    ->get();
                //Log::info("外接设备:".json_encode($devices));
                $flag=true;
                $msg="";
                try {
                    foreach($devices as $device){

                        if($online_flag==1){
                            //宇泛在线
                            Log::info("online");
                            $response = $http->get($faceMaven.'/onlineUser/update', [
                                'query' => [
                                    'id'=>$customer->id,
                                    'name'=>$customer->user_name,
                                    'cardNum'=>'',
                                    'idCardNo'=>$customer->id_license,
                                    'phone'=>$customer->telephone,
                                    'deviceKey' => $device->deviceKey,
                                    'appKey' => $shop->appKey,
                                    'appSecret'=>$shop->appSecret,
                                    'appId'=>$shop->appId,
                                    'personGuid'=>$customer->personGuid,
                                ],
                            ]);
                        }else{
                            //if($shop!=null&&($shop->face_flag!=0)){
                            $ip=$device->url;
                            Log::info("update发送的外网ip:".$ip);
                            $response = $http->get($faceMaven.'/person/update', [
                                'query' => [
                                    'ip' => $ip,
                                    'pass' => 'admin123',
                                    'id'=>$customer->id,
                                    'name'=>$customer->user_name,
                                    'idcardNum'=>"",
                                ],
                            ]);

                        }

                        $res = json_decode($response->getBody(), true);
                        //log::info("res:".json_encode($res));
                        $result=$res["success"];
                        if($result==0){
                            $flag=false;
                            $msg=$res["data"];
                            break;
                        }
                    }
                }catch (\Throwable $throwable){
                    if ($throwable instanceof ClientException) {
                        //doing something
                        throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.customer.net_error'));
                    }
                    if ($throwable instanceof ServerException) {
                        //doing something
                        throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.customer.net_error'));
                    }
                    throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.customer.net_error'));
                }
                if($flag){
                    DB::commit();
                }else{
                    DB::rollBack();
                    throw new ApiException(ErrorCode::DATABASE_ERROR, $msg);
                }
            }else{
                DB::commit();
            }

            return $customer;
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.customer.update_error'));
        }
    }

    /**
     * @param Customer $customer
     * @param $enabled
     * @return bool
     * @throws ApiException
     */
    public function mark(Customer $customer, $enabled)
    {
        $customer->enabled = $enabled;

        if ($customer->save()) {
            return true;
        }

        throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.customer.mark_error'));
    }

    /**
     * @param Customer $customer
     * @return mixed
     */
    public function getAccountRecord(Customer $customer, $input)
    {
        $query = $customer->account_records();
        if (isset($input['start_time']) && isset($input['end_time']))
        {
            $query = $query->whereBetween('created_at', [$input['start_time'].' 00:00:00', $input['end_time']." 23:59:59"]);
        }

        $records = $query->orderBy('created_at', 'desc')->paginate(15);

        return $records;
    }

    /**
     * @param $entityCardId
     */
    private function createAccount($customer_id, $balance)
    {
        $account = new Account();
        $account->customer_id = $customer_id;
        $account->balance = $balance;
        $account->save();
    }

    /**
     * @param $input
     * @return Customer
     */
    private function createCustomerStub($input)
    {
        $customer = new Customer();
        $customer->restaurant_id = $input['restaurant_id'];
        $customer->shop_id = $input['shop_id'];
        $customer->user_name = $input['user_name'];
        $customer->telephone = isset($input['telephone']) ? $input['telephone']: '';
        $customer->id_license = isset($input['id_license']) ? $input['id_license'] : '';
        $customer->birthday = isset($input['birthday']) ? $input['birthday'] : null;
        $customer->department_id = isset($input['department_id']) ? $input['department_id'] : null;
        $customer->consume_category_id = isset($input['consume_category_id']) ? $input['consume_category_id'] : null;
        $customer->enabled = isset($input['enabled']) ? $input['enabled'] : true;

        return $customer;
    }

}
