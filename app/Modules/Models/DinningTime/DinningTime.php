<?php

namespace App\Modules\Models\DinningTime;

use App\Modules\Models\DinningTime\Traits\Attribute\DinningTimeAttribute;
use App\Modules\Models\DinningTime\Traits\Relationship\DinningTimeRelationship;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class DinningTime extends Model
{
    use DinningTimeAttribute, DinningTimeRelationship;

    protected $fillable = ['name', 'start_time', 'end_time', 'enabled'];

    protected $table = 'dinning_time';

    public function getStartTimeAttribute($value)
    {
        return Carbon::parse($value)->format("H:i");
    }

    public function getEndTimeAttribute($value)
    {
        return Carbon::parse($value)->format("H:i");
    }
}