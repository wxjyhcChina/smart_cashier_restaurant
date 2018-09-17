<?php

namespace App\Modules\Repositories\Card;

use App\Modules\Models\Card\Card;
use App\Modules\Repositories\BaseRepository;

/**
 * Class BaseCardRepository.
 */
class BaseCardRepository extends BaseRepository
{

    /**
     * Associated Repository Model.
     */
    const MODEL = Card::class;

}
