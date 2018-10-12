<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 13/04/2017
 * Time: 2:22 PM
 */

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;

class AuthUtil
{
    public static function checkOrFail($guard)
    {
        $payload = Auth::guard($guard)->checkOrFail();
        if ($payload)
        {
            $payload = $payload->getClaims()->toPlainArray();
        }

        return $payload;
    }
}