<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Backend\Label;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\Label\Label;
use App\Modules\Models\Label\LabelCategory;
use App\Modules\Repositories\Label\BaseLabelCategoryRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class LabelCategoryRepository
 * @package App\Repositories\Backend\Label
 */
class LabelCategoryRepository extends BaseLabelCategoryRepository
{
    /**
     * @param $labelCategory
     * @param $input
     * @throws \Throwable
     */
    public function assignLabel($labelCategory, $input)
    {
        DB::transaction(function() use ($input, $labelCategory) {
            Label::where('label_category_id', $labelCategory->id)->update(['label_category_id' => null]);

            if(array_key_exists('id', $input))
            {
                $ids = $input['id'];

                for($i =0 ; $i < count($ids); $i++)
                {
                    $label = Label::find($ids[$i]);
                    $label->label_category_id = $labelCategory->id;
                    $label->save();
                }
            }
        });
    }
}