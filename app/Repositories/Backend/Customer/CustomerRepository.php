<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Backend\Customer;

use App\Exceptions\GeneralException;
use App\Modules\Models\Card\Card;
use App\Modules\Models\Customer\Account;
use App\Modules\Models\Customer\Customer;
use App\Modules\Repositories\Customer\BaseCustomerRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class CustomerRepository
 * @package App\Repositories\Backend\Customer
 */
class CustomerRepository extends BaseCustomerRepository
{
    /**
     * @param $input
     * @return Customer
     * @throws GeneralException
     */
    public function create($input)
    {
        $customer = $this->createCustomerStub($input);

        try
        {
            DB::beginTransaction();

            if ($customer->save())
            {
                $card = Card::findOrFail($input['card_id']);
                $card->customer_id = $customer->id;
                $card->save();

                $this->createAccount($customer->id);
            }

            DB::commit();
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            throw new GeneralException(trans('exceptions.backend.customer.create_error'));
        }

        return $customer;
    }

    /**
     * @param Customer $customer
     * @param $input
     * @throws GeneralException
     */
    public function update(Customer $customer, $input)
    {
        Log::info("customer update param:".json_encode($input));

        try
        {
            DB::beginTransaction();
            $customer->update($input);

            DB::commit();
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            throw new GeneralException(trans('exceptions.backend.customer.update_error'));
        }
    }

    /**
     * @param Customer $customer
     * @param $enabled
     * @return bool
     * @throws GeneralException
     */
    public function mark(Customer $customer, $enabled)
    {
        $customer->enabled = $enabled;

        if ($customer->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.customer.mark_error'));
    }

    /**
     * @param $entityCardId
     */
    private function createAccount($customer_id)
    {
        $account = new Account();
        $account->customer_id = $customer_id;
        $account->balance = 0;
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
        $customer->user_name = $input['user_name'];
        $customer->id_license = isset($input['id_license']) ? $input['id_license'] : '';
        $customer->birthday = isset($input['birthday']) ? $input['birthday'] : '';
        $customer->department_id = $input['department_id'];
        $customer->consume_category_id = $input['consume_category_id'];

        return $customer;
    }
}