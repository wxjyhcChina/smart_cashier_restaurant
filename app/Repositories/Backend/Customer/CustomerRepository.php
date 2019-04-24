<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Backend\Customer;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\AccountRecordType;
use App\Modules\Enums\CardStatus;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\Card\Card;
use App\Modules\Models\Customer\Customer;
use App\Modules\Repositories\Customer\BaseCustomerRepository;
use App\Modules\Services\Account\Facades\Account;
use Illuminate\Support\Facades\DB;

/**
 * Class CustomerRepository
 * @package App\Repositories\Backend\Customer
 */
class CustomerRepository extends BaseCustomerRepository
{
    /**
     * @param $restaurant_id
     * @return mixed
     */
    public function getByRestaurantQuery($restaurant_id)
    {
        return $this->query()
            ->select('customers.*', 'cards.number as card_number', 'departments.name as department_name', 'consume_categories.name as consume_category_name', 'accounts.balance as account_balance', 'accounts.subsidy_balance as account_subsidy_balance')
            ->leftJoin('cards', 'cards.customer_id', '=', 'customers.id')
            ->leftJoin('departments', 'departments.id', '=', 'customers.department_id')
            ->leftJoin('consume_categories', 'consume_categories.id', '=', 'customers.consume_category_id')
            ->leftJoin('accounts', 'accounts.customer_id', '=', 'customers.id')
            ->where('customers.restaurant_id', $restaurant_id);
    }

    /**
     * @param Customer $customer
     * @return mixed
     */
    public function getCustomerConsumeOrderQuery(Customer $customer)
    {
        return $customer->consume_orders()->select('consume_orders.*', 'customers.user_name as customer_name', 'cards.number as card_number', 'dinning_time.name as dinning_time_name', 'restaurant_users.username as restaurant_user_name')
            ->leftJoin('customers', 'consume_orders.customer_id', '=', 'customers.id')
            ->leftJoin('cards', 'consume_orders.card_id', '=', 'cards.id')
            ->leftJoin('dinning_time', 'consume_orders.dinning_time_id', '=', 'dinning_time.id')
            ->leftJoin('restaurant_users', 'consume_orders.restaurant_user_id', '=', 'restaurant_users.id');
    }


    /**
     * @param $customer
     * @param $source
     * @param $balance
     * @return mixed
     */
    public function changeBalance($customer, $source, $balance)
    {
        if ($source == AccountRecordType::SYSTEM_MINUS) {
            $balance = -$balance;
        }

        if ($balance != 0) {
            $account = $customer->account;
            $account->subsidy_balance = $account->subsidy_balance + $balance;
            $account->save();

            Account::addRecord($customer->id, $account->id, $source, abs($balance), null, null);
        }
    }

    /**
     * @param $source
     * @param $type
     * @param $ids
     * @param $restaurant_id
     * @throws \Throwable
     */
    public function clearMultipleBalance($type, $ids, $restaurant_id)
    {
        $customersQuery = Customer::where('restaurant_id', $restaurant_id);

        if ($type == 'department')
        {
            $customersQuery = $customersQuery->whereIn('department_id', $ids);
        }
        else if ($type == 'customer')
        {
            $customersQuery = $customersQuery->whereIn('id', $ids);
        }

        $customers = $customersQuery->get();

        DB::transaction(function () use ($customers) {
            foreach ($customers as $customer) {
                $account = $customer->account;
                $subsidy_balance = $account->subsidy_balance;

                if ($subsidy_balance > 0)
                {
                    $account->subsidy_balance = 0;
                    $account->save();

                    Account::addRecord($customer->id, $account->id, AccountRecordType::SYSTEM_MINUS, abs($subsidy_balance), null, null);
                }
            }
        });
    }

    /**
     * @param $source
     * @param $balance
     * @param $restaurant_id
     * @throws \Throwable
     */
    public function changeMultipleBalance($source, $balance, $type, $ids, $restaurant_id)
    {
        if ($source == AccountRecordType::SYSTEM_MINUS) {
            $balance = -$balance;
        }

        if ($balance != 0) {

            $customersQuery = Customer::where('restaurant_id', $restaurant_id);

            if ($type == 'department')
            {
                $customersQuery = $customersQuery->whereIn('department_id', $ids);
            }
            else if ($type == 'customer')
            {
                $customersQuery = $customersQuery->whereIn('id', $ids);
            }

            $customers = $customersQuery->get();

            DB::transaction(function () use ($customers, $balance, $source) {
                foreach ($customers as $customer) {
                    $account = $customer->account;
                    $account->subsidy_balance = $account->subsidy_balance + $balance;
                    $account->save();

                    Account::addRecord($customer->id, $account->id, $source, abs($balance), null, null);
                }
            });
        }
    }

    public function bindCard($customer, $input)
    {
        $card = Card::where('internal_number', $input['card_id'])->first();
        if ($card == null)
        {
            throw new ApiException(ErrorCode::RESOURCE_NOT_FOUND, trans('api.error.card_not_exist'));
        }
        else if ($card->status != CardStatus::UNACTIVATED)
        {
            throw new ApiException(ErrorCode::CARD_STATUS_INCORRECT, trans('api.error.card_status_incorrect'));
        }

        $card->customer_id = $customer->id;
        $card->status = CardStatus::ACTIVATED;
        $card->save();
    }

    public function unbindCard($customer)
    {
        $card = $customer->card;

        if ($card == null)
        {
            throw new ApiException(ErrorCode::RESOURCE_NOT_FOUND, trans('api.error.card_not_exist'));
        }

        $card->status = CardStatus::UNACTIVATED;
        $card->customer_id = null;
        $card->save();
    }

    public function lostCard($customer, $input)
    {
        $originalCard = $customer->card;

        if ($originalCard == null)
        {
            throw new ApiException(ErrorCode::RESOURCE_NOT_FOUND, trans('api.error.card_not_exist'));
        }

        $originalCard->status = CardStatus::LOST;
        $originalCard->customer_id = null;
        $originalCard->save();

        $card = Card::where('internal_number', $input['card_id'])->first();
        if ($card == null)
        {
            throw new ApiException(ErrorCode::RESOURCE_NOT_FOUND, trans('api.error.card_not_exist'));
        }
        else if ($card->status != CardStatus::UNACTIVATED)
        {
            throw new ApiException(ErrorCode::CARD_STATUS_INCORRECT, trans('api.error.card_status_incorrect'));
        }

        $card->customer_id = $customer->id;
        $card->status = CardStatus::ACTIVATED;
        $card->save();
    }
}