<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Api\Label;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\Label\Label;
use App\Modules\Repositories\Label\BaseLabelRepository;

/**
 * Class LabelRepository
 * @package App\Repositories\Backend\Label
 */
class LabelRepository extends BaseLabelRepository
{
    /**
     * @param $conditions
     * @return  Label
     * @throws ApiException
     */
    public function findOne($conditions)
    {
        $conditionCollection = collect($conditions);
        $conditionCollection = $conditionCollection->only(['id', 'rfid']);

        $label = $this->query()
            ->where($conditionCollection->toArray())
            ->with('label_category')
            ->first();

        if ($label == null)
        {
            throw new ApiException(ErrorCode::RESOURCE_NOT_FOUND, trans('api.error.label_not_exist'), 404);
        }

        return $label;
    }
}