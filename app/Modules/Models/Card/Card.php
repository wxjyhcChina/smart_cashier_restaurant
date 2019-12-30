<?php

namespace App\Modules\Models\Card;

use App\Modules\Enums\CardStatus;
use App\Modules\Models\Card\Traits\Attribute\CardAttribute;
use App\Modules\Models\Card\Traits\Relationship\CardRelationship;
use Illuminate\Database\Eloquent\Model;


class Card extends Model
{
    use CardAttribute, CardRelationship;

    protected $fillable = ['id', 'restaurant_id', 'customer_id', 'number', 'internal_number', 'customer_id', 'status', 'enabled'];

    public function getShowStatusAttribute()
    {
        $status = $this->status;

        $str = '';
        switch ($status)
        {
            case CardStatus::ACTIVATED:
                $str = trans('labels.backend.card.status.activated');
                break;
            case CardStatus::UNACTIVATED:
                $str = trans('labels.backend.card.status.unactivated');
                break;
            case CardStatus::LOST:
                $str = trans('labels.backend.card.status.lost');
                break;
        }

        return $str;
    }
}