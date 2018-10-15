<?php

namespace App\Modules\Models\Label;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $fillable = ['id', 'label_category_id', 'rfid'];

    protected $table = 'goods';
}