<?php

namespace App\Modules\Repositories\Customer;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\CardStatus;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\Card\Card;
use App\Modules\Models\Customer\Account;
use App\Modules\Models\Customer\Customer;
use App\Modules\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            }

            DB::commit();
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            if ($exception instanceof ApiException)
            {
                throw $exception;
            }

            throw $exception;

//            throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.customer.create_error'));
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

            DB::commit();

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
