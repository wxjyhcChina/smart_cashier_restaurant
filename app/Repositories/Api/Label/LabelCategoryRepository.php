<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Api\Label;

use App\Modules\Models\Label\Label;
use App\Modules\Repositories\Label\BaseLabelCategoryRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class LabelCategoryRepository
 * @package App\Repositories\Backend\Label
 */
class LabelCategoryRepository extends BaseLabelCategoryRepository
{
    /**
     * @param $restaurant_id
     * @param $input
     * @param $size
     * @return mixed
     */
    public function getByRestaurant($restaurant_id, $input, $size=15)
    {
        $query = $this->getByRestaurantQuery($restaurant_id);

        if (isset($input['key']))
        {
            $query->where('name', 'like', '%'.$input['key'].'%')->where('restaurant_id', $restaurant_id);
        }

        return $query->paginate($size);
    }

    public function getByShop($shop_id, $input, $size=15)
    {
        $query = $this->getByShopQuery($shop_id);

        if (isset($input['key']))
        {
            $query->where('name', 'like', '%'.$input['key'].'%')->where('shop_id', $shop_id);
        }

        return $query->paginate($size);
    }

    /**
     * @param $labelCategory
     * @param $labels
     * @throws \Throwable
     */
    public function assignLabel($labelCategory, $labels)
    {
        DB::transaction(function() use ($labels, $labelCategory) {
            foreach ($labels as $rfid)
            {
                $label = Label::where('rfid', $rfid)->first();

                if ($label == null)
                {
                    $label = new Label();
                    $label->rfid = $rfid;
                }

                $label->label_category_id = $labelCategory->id;
                $label->save();
            }
        });
    }
}