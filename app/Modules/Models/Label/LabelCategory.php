<?php

namespace App\Modules\Models\Label;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LabelCategory extends Model
{
    protected $fillable = ['name', 'image'];

    protected $table = 'goods';
}