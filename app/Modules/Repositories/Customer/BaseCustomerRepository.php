<?php

namespace App\Modules\Repositories\Customer;

use App\Modules\Models\Customer\Customer;
use App\Modules\Repositories\BaseRepository;

/**
 * Class BaseCustomerRepository.
 */
class BaseCustomerRepository extends BaseRepository
{

    /**
     * Associated Repository Model.
     */
    const MODEL = Customer::class;


    public function getByRestaurantQuery($restaurant_id)
    {
        return $this->query()
            ->select('customers.*', 'cards.number as card_number', 'departments.name as department_name', 'consume_categories.name as consume_category_name')
            ->leftJoin('cards', 'cards.customer_id', '=', 'customers.id')
            ->leftJoin('departments', 'departments.id', '=', 'customers.department_id')
            ->leftJoin('consume_categories', 'consume_categories.id', '=', 'customers.consume_category_id')
            ->where('customers.restaurant_id', $restaurant_id);
    }

}
