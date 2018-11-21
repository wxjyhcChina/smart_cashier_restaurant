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
     * @return mixed
     */
    public function getByRestaurant($restaurant_id)
    {
        return $this->getByRestaurantQuery($restaurant_id)->get();
    }

    /**
     * @param $labelCategory
     * @param $labels
     * @throws \Throwable
     */
    public function assignLabel($labelCategory, $labels)
    {
        DB::transaction(function() use ($labels, $labelCategory) {
            Label::where('label_category_id', $labelCategory->id)->update(['label_category_id' => null]);

            foreach ($labels as $label)
            {
                $label = Label::where('rfid', $label)->first();
                $label->label_category_id = $labelCategory->id;
                $label->save();
            }
        });
    }
}