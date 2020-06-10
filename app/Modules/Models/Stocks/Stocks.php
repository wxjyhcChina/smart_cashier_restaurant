<?php

namespace App\Modules\Models\Stocks;

use App\Modules\Models\Stocks\Traits\Attribute\StocksAttribute;
use App\Modules\Models\Stocks\Traits\Relationship\StocksRelationship;
use Illuminate\Database\Eloquent\Model;

class Stocks extends Model
{
    use StocksAttribute, StocksRelationship;

    protected $fillable = ['id','material_id', 'restaurant_id', 'shop_id', 'count'];

    protected $table = 'stocks';

    protected $hidden = ['pivot'];
}