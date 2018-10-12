<?php

namespace App\Http\Middleware;

use App\Modules\Enums\ErrorCode;
use App\Exceptions\Api\ApiException;
use Closure;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

/**
 * Class ApiAuthenticate
 * @package App\Http\Middleware
 */
class ApiAuthenticate
{

    /**
     * @param $request
     * @param Closure $next
     * @param null $guard
     * @return mixed
     * @throws ApiException
     */

    //TODO:check whether user already disabled
    public function handle($request, Closure $next, $guard = null)
    {
        if (! $token = Auth('api')->setRequest($request)->getToken()) {
            throw new ApiException(ErrorCode::TOKEN_NOT_PROVIDED, trans("api.error.token_not_provide"), array());
        }

        try {
            $payload = AuthUtil::checkOrFail('api');
        } catch (TokenExpiredException $e) {
            throw new ApiException(ErrorCode::TOKEN_EXPIRE, trans("api.error.token_expire"), array());
        } catch (JWTException $e) {
            throw new ApiException(ErrorCode::TOKEN_INVALID, trans("api.error.token_invalid"), array());
        }

        if (! $payload) {
            throw new ApiException(ErrorCode::USER_NOT_EXIST, trans("api.error.user_not_exist"), array());
        }

        config()->set('auth.defaults.guard','api');

        return $next($request);
    }
}