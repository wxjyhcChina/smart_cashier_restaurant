<?php

namespace App\Http\Middleware;

use App\Modules\Enums\ErrorCode;
use App\Exceptions\Api\ApiException;
use App\Modules\Models\Restaurant\Restaurant;
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
            throw new ApiException(ErrorCode::TOKEN_NOT_PROVIDED, trans("api.error.token_not_provide"), 401);
        }

        try {
            $payload = AuthUtil::checkOrFail('api');
        } catch (TokenExpiredException $e) {
            throw new ApiException(ErrorCode::TOKEN_EXPIRE, trans("api.error.token_expire"), 401);
        } catch (JWTException $e) {
            throw new ApiException(ErrorCode::TOKEN_INVALID, trans("api.error.token_invalid"), 401);
        }

        if (! $payload) {
            throw new ApiException(ErrorCode::USER_NOT_EXIST, trans("api.error.user_not_exist"), 401);
        }

        $user = Auth('api')->User();
        if (Restaurant::find($user->restaurant_id)->enabled == false)
        {
            Auth('api')->invalidate();
            throw new ApiException(ErrorCode::RESTAURANT_BLOCKED, trans('api.error.restaurant_blocked'));
        }

        config()->set('auth.defaults.guard','api');

        return $next($request);
    }
}