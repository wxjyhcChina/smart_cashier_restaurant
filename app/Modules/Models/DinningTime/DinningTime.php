<?php

namespace App\Modules\Models\DinningTime;

use App\Modules\Models\DinningTime\Traits\Attribute\DinningTimeAttribute;
use App\Modules\Models\DinningTime\Traits\Relationship\DinningTimeRelationship;
use Illuminate\Database\Eloquent\Model;


class DinningTime extends Model
{
    use DinningTimeAttribute, DinningTimeRelationship;

    protected $fillable = ['name', 'start_time', 'end_time'];

    protected $table = 'dinning_time';
}