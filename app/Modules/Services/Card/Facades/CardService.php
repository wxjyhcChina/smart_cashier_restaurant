<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 20/09/2017
 * Time: 10:20 AM
 */
namespace App\Modules\Services\Card\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class ConsumeOrder
 * @package App\Modules\Services\Account\Facades
 */
class CardService extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cardService';
    }
}