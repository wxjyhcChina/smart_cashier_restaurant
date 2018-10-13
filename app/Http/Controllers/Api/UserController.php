<?php

namespace App\Http\Controllers\Api;

use App\Access\Repository\User\UserRepository;
use App\Exceptions\Api\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UserLoginRequest;
use App\Modules\Enums\ErrorCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class UserController
 * @package App\Http\Controllers\Api
 */
class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @param UserRepository $users
    **/
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * @param UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\ApiException
     */
    public function login(UserLoginRequest $request)
    {
        Log::info("params".json_encode($request->all()));

        $response = $this->users->login($request->only("username", "password"));

        return $this->responseSuccess($response);
    }

    /**
     * Get current user info
     * @param Request $request
     * @return string
     */
    public function current(Request $request)
    {
        $user = Auth('api')->user();

        return response()->json($user);
    }

    /**
     * Logout User
     * @param Request $request
     * @return string
     */
    public function logout(Request $request)
    {
        $this->users->logout();

        return $this->responseSuccess();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\ApiException
     */
    public function refreshToken(Request $request){
        $response = $this->users->refreshToken();

        return $this->responseSuccess($response);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\ApiException
     */
    public function validateToken(Request $request){
        $response = $this->users->validateToken();

        return $this->responseSuccess($response);
    }
}