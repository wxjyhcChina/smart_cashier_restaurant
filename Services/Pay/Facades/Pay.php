<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 20/09/2017
 * Time: 10:20 AM
 */
namespace App\Modules\Services\Pay\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Pay
 * @package App\Modules\Services\Pay\Facades
 */
class Pay extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'pay';
    }
}