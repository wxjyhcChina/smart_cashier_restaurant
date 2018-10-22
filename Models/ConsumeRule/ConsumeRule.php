<?php

namespace App\Modules\Models\ConsumeRule;

use App\Modules\Models\ConsumeRule\Traits\Relationship\ConsumeRuleRelationship;
use App\Modules\Models\ConsumeRule\Traits\Attribute\ConsumeRuleAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ConsumeRule extends Model
{
    use ConsumeRuleAttribute, ConsumeRuleRelationship, SoftDeletes;

    protected $fillable = ['restaurant_id', 'name', 'discount', 'weekday', 'enabled'];

    //parse weekday
    public function getWeekdayAttribute($value)
    {
        $weekdayStr = sprintf("%07d", decbin($value));
        $weekdayArray = [];
        for ($i = strlen($weekdayStr) - 1; $i >= 0 ; $i--)
        {
            if ($weekdayStr[$i] == 1)
            {
                array_push($weekdayArray, strlen($weekdayStr) - $i - 1);
            }
        }

        return $weekdayArray;
    }
}