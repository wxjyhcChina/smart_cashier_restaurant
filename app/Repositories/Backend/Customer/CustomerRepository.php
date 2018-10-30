<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Backend\Customer;

use App\Modules\Enums\AccountRecordType;
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
            ->select('customers.*', 'cards.number as card_number', 'departments.name as department_name', 'consume_categories.name as consume_category_name', 'accounts.balance as account_balance')
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
        if ($source == AccountRecordType::SYSTEM_MINUS)
        {
            $balance = -$balance;
        }

        if ($balance != 0)
        {
            $account = $customer->account;
            $account->balance = $account->balance + $balance;
            $account->save();

            Account::addRecord($customer->id, $account->id, $source, abs($balance), null, null);
        }
    }


    /**
     * @param $source
     * @param $balance
     * @param $restaurant_id
     * @throws \Throwable
     */
    public function changeAllBalance($source, $balance, $restaurant_id)
    {
        if ($source == AccountRecordType::SYSTEM_MINUS)
        {
            $balance = -$balance;
        }

        if ($balance != 0)
        {
            $customers = Customer::where('restaurant_id', $restaurant_id)->get();

            DB::transaction(function () use ($customers, $balance, $source) {
                foreach ($customers as $customer)
                {
                    $account = $customer->account;
                    $account->balance = $account->balance + $balance;
                    $account->save();

                    Account::addRecord($customer->id, $account->id, $source, abs($balance), null, null);
                }
            });
        }
    }
}