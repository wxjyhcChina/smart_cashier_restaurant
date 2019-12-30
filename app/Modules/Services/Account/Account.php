<?php

namespace App\Modules\Services\Account;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\AccountRecordType;
use App\Modules\Enums\ErrorCode;
use App\Modules\Enums\PayMethodType;
use App\Modules\Models\Customer\AccountRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


/**
 * Class Account.
 */
class Account
{
    /**
     * @param $customer_id
     * @param $account_id
     * @param $type
     * @param $money
     * @param $order_id
     * @param $pay_method_id
     */
    public function addRecord($customer_id, $account_id, $type, $money, $order_id, $pay_method)
    {
        $account_record = new AccountRecord();
        $account_record->customer_id = $customer_id;
        $account_record->account_id = $account_id;
        $account_record->type = $type;

        switch ($type)
        {
            case AccountRecordType::RECHARGE:
            case AccountRecordType::REFUND:
                $account_record->recharge_order_id = $order_id;
                break;
            case AccountRecordType::CONSUME:
                $account_record->consume_order_id = $order_id;
                break;
        }

        $account_record->money = $money;
        $account_record->pay_method = $pay_method;
        $account_record->save();
    }

    /**
     * @param $account
     * @param $price
     * @return bool
     * @throws ApiException
     */
    public function compareBalance($account, $price)
    {
        $balance = $account->balance + $account->subsidy_balance;
        if (bccomp($balance, $price, 2) == -1)
        {
            throw  new ApiException(ErrorCode::BALANCE_NOT_ENOUGH, trans('api.error.balance_not_enough'));
        }

        return true;
    }

    /**
     * @param $order_id
     * @param $account
     * @param $money
     * @param $pay_method
     */
    public function rechargeAccount($order_id, $account, $money, $pay_method)
    {
        Log::info('[payAccount]order id is:'.$order_id.', account id is '.$account->id);
        $account->balance = bcadd($account->balance, $money, 2);
        $account->save();

        $this->addRecord($account->customer_id, $account->id, AccountRecordType::RECHARGE, $money, $order_id, $pay_method);
    }
    
    /**
     * @param $order_id
     * @param $account
     * @param $money
     */
    public function payAccount($order_id, $account, $money)
    {
        Log::info('[payAccount]order id is:'.$order_id.', account id is '.$account->id);

        if ($account->subsidy_balance >= $money)
        {
            $account->subsidy_balance = bcsub($account->balance, $money, 2);
        }
        else
        {
            $remainingMoney = bcsub($money, $account->subsidy_balance, 2);

            $account->subsidy_balance = 0;
            $account->balance = bcsub($account->balance, $remainingMoney, 2);
        }

        $account->save();

        $this->addRecord($account->customer_id, $account->id, AccountRecordType::CONSUME, $money, $order_id, PayMethodType::CARD);
    }
}
