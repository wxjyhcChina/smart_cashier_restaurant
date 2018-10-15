<?php

namespace App\Modules\Models\Goods;

use App\Modules\Models\Goods\Traits\Attribute\GoodsAttribute;
use App\Modules\Models\Goods\Traits\Relationship\GoodsRelationship;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Goods extends Model
{
    use GoodsAttribute, GoodsRelationship, SoftDeletes;

    protected $fillable = ['name', 'price', 'image'];

    protected $table = 'goods';
}