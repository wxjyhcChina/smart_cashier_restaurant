<?php

namespace App\Access\Repository\User;

use App\Access\Model\User\User;
use App\Exceptions\Api\ApiException;
use App\Http\Middleware\AuthUtil;
use App\Modules\Enums\ErrorCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use App\Events\Backend\Access\User\UserCreated;
use App\Events\Backend\Access\User\UserDeleted;
use App\Events\Backend\Access\User\UserUpdated;
use App\Events\Backend\Access\User\UserRestored;
use App\Events\Backend\Access\User\UserConfirmed;
use App\Events\Backend\Access\User\UserDeactivated;
use App\Events\Backend\Access\User\UserReactivated;
use App\Events\Backend\Access\User\UserUnconfirmed;
use App\Events\Backend\Access\User\UserPasswordChanged;
use App\Notifications\Backend\Access\UserAccountActive;
use App\Access\Repository\Role\RoleRepository;
use App\Events\Backend\Access\User\UserPermanentlyDeleted;
use App\Notifications\Frontend\Auth\UserNeedsConfirmation;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = User::class;

    /**
     * @var RoleRepository
     */
    protected $role;

    /**
     * @param RoleRepository $role
     */
    public function __construct(RoleRepository $role)
    {
        $this->role = $role;
    }

    /**
     * @param        $permissions
     * @param string $by
     *
     * @return mixed
     */
    public function getByPermission($permissions, $by = 'name')
    {
        if (! is_array($permissions)) {
            $permissions = [$permissions];
        }

        return $this->query()->whereHas('roles.permissions', function ($query) use ($permissions, $by) {
            $query->whereIn('permissions.'.$by, $permissions);
        })->get();
    }

    /**
     * @param        $roles
     * @param string $by
     *
     * @return mixed
     */
    public function getByRole($roles, $by = 'name')
    {
        if (! is_array($roles)) {
            $roles = [$roles];
        }

        return $this->query()->whereHas('roles', function ($query) use ($roles, $by) {
            $query->whereIn('roles.'.$by, $roles);
        })->get();
    }

    /**
     * @param int  $status
     * @param bool $trashed
     *
     * @return mixed
     */
    public function getForDataTable($status = 1, $trashed = false)
    {
        $user = Auth::user();
        /**
         * Note: You must return deleted_at or the User getActionButtonsAttribute won't
         * be able to differentiate what buttons to show for each row.
         */
        $dataTableQuery = $this->query()
            ->with('roles')
            ->where('restaurant_id', $user->restaurant_id)
            ->select([
                config('access.users_table').'.id',
                config('access.users_table').'.username',
                config('access.users_table').'.first_name',
                config('access.users_table').'.last_name',
                config('access.users_table').'.status',
                config('access.users_table').'.created_at',
                config('access.users_table').'.updated_at',
                config('access.users_table').'.deleted_at',
            ]);

        if ($trashed == 'true') {
            return $dataTableQuery->onlyTrashed();
        }
        else{
            return $dataTableQuery->withTrashed();
        }

        // active() is a scope on the UserScope trait
        return $dataTableQuery->active($status);
    }

    /**
     * @return mixed
     */
    public function getUnconfirmedCount()
    {
        return $this->query()->where('confirmed', 0)->count();
    }

    /**
     * @param array $input
     */
    public function create($input)
    {
        $data = $input['data'];
        $roles = $input['roles'];

        $user = $this->createUserStub($data);

        DB::transaction(function () use ($user, $data, $roles) {
            if ($user->save()) {
                //User Created, Validate Roles
                if (! count($roles['assignees_roles'])) {
                    throw new GeneralException(trans('exceptions.backend.access.users.role_needed_create'));
                }

                //Attach new roles
                $user->attachRoles($roles['assignees_roles']);


                event(new UserCreated($user));

                return true;
            }

            throw new GeneralException(trans('exceptions.backend.access.users.create_error'));
        });
    }

    /**
     * @param Model $user
     * @param array $input
     *
     * @return bool
     * @throws GeneralException
     */
    public function update(Model $user, array $input)
    {
        $data = $input['data'];
        $roles = $input['roles'];

        $this->checkUserByUsername($data, $user);

        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->username = $data['username'];
        $user->status = isset($data['status']) ? 1 : 0;

        DB::transaction(function () use ($user, $data, $roles) {
            if ($user->save()) {
                $this->checkUserRolesCount($roles);
                $this->flushRoles($roles, $user);

                event(new UserUpdated($user));

                return true;
            }

            throw new GeneralException(trans('exceptions.backend.access.users.update_error'));
        });
    }

    /**
     * @param Model $user
     * @param $input
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function updatePassword(Model $user, $input)
    {
        $user->password = bcrypt($input['password']);

        if ($user->save()) {
            event(new UserPasswordChanged($user));

            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.update_password_error'));
    }

    /**
     * @param Model $user
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function delete(Model $user)
    {
        if (access()->id() == $user->id) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_delete_self'));
        }

        if ($user->id == 1) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_delete_admin'));
        }

        if ($user->delete()) {
            event(new UserDeleted($user));

            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.delete_error'));
    }

    /**
     * @param Model $user
     *
     * @throws GeneralException
     */
    public function forceDelete(Model $user)
    {
        if (is_null($user->deleted_at)) {
            throw new GeneralException(trans('exceptions.backend.access.users.delete_first'));
        }

        DB::transaction(function () use ($user) {
            if ($user->forceDelete()) {
                event(new UserPermanentlyDeleted($user));

                return true;
            }

            throw new GeneralException(trans('exceptions.backend.access.users.delete_error'));
        });
    }

    /**
     * @param Model $user
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function restore(Model $user)
    {
        if (is_null($user->deleted_at)) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_restore'));
        }

        if ($user->restore()) {
            event(new UserRestored($user));

            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.restore_error'));
    }

    /**
     * @param Model $user
     * @param $status
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function mark(Model $user, $status)
    {
        if (access()->id() == $user->id && $status == 0) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_deactivate_self'));
        }

        $user->status = $status;

        switch ($status) {
            case 0:
                event(new UserDeactivated($user));
            break;

            case 1:
                event(new UserReactivated($user));
            break;
        }

        if ($user->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.mark_error'));
    }

    /**
     * @param Model $user
     *
     * @return bool
     * @throws GeneralException
     */
    public function confirm(Model $user)
    {
        if ($user->confirmed == 1) {
            throw new GeneralException(trans('exceptions.backend.access.users.already_confirmed'));
        }

        $user->confirmed = 1;
        $confirmed = $user->save();

        if ($confirmed) {
            event(new UserConfirmed($user));

            // Let user know their account was approved
            if (config('access.users.requires_approval')) {
                $user->notify(new UserAccountActive());
            }

            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.cant_confirm'));
    }

    /**
     * @param Model $user
     *
     * @return bool
     * @throws GeneralException
     */
    public function unconfirm(Model $user)
    {
        if ($user->confirmed == 0) {
            throw new GeneralException(trans('exceptions.backend.access.users.not_confirmed'));
        }

        if ($user->id == 1) {
            // Cant un-confirm admin
            throw new GeneralException(trans('exceptions.backend.access.users.cant_unconfirm_admin'));
        }

        if ($user->id == access()->id()) {
            // Cant un-confirm self
            throw new GeneralException(trans('exceptions.backend.access.users.cant_unconfirm_self'));
        }

        $user->confirmed = 0;
        $unconfirmed = $user->save();

        if ($unconfirmed) {
            event(new UserUnconfirmed($user));

            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.cant_unconfirm')); // TODO
    }

    /**
     * @param  $input
     * @param  $user
     *
     * @throws GeneralException
     */
    protected function checkUserByUsername($input, $user)
    {
        //Figure out if username is not the same
        if ($user->username != $input['username']) {
            //Check to see if username exists
            if ($this->query()->where('username', '=', $input['username'])->first()) {
                throw new GeneralException(trans('exceptions.backend.access.users.username_error'));
            }
        }
    }

    /**
     * @param $roles
     * @param $user
     */
    protected function flushRoles($roles, $user)
    {
        //Flush roles out, then add array of new ones
        $user->detachRoles($user->roles);
        $user->attachRoles($roles['assignees_roles']);
    }

    /**
     * @param  $roles
     *
     * @throws GeneralException
     */
    protected function checkUserRolesCount($roles)
    {
        //User Updated, Update Roles
        //Validate that there's at least one role chosen
        if (count($roles['assignees_roles']) == 0) {
            throw new GeneralException(trans('exceptions.backend.access.users.role_needed'));
        }
    }

    /**
     * @param  $input
     *
     * @return mixed
     */
    protected function createUserStub($input)
    {
        $user = self::MODEL;
        $user = new $user;
        $user->first_name = $input['first_name'];
        $user->last_name = $input['last_name'];
        $user->username = $input['username'];
        $user->password = bcrypt($input['password']);
        $user->status = isset($input['status']) ? 1 : 0;

        return $user;
    }

    /**
     * @param $credentials
     * @return array
     * @throws ApiException
     */
    public function login($credentials)
    {
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = Auth('api')->attempt($credentials)) {
                throw new ApiException(ErrorCode::LOGIN_FAILED, trans('api.error.login_failed'), 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            throw new ApiException(ErrorCode::CREATE_TOKEN_FAILED, trans('api.error.create_token_failed'), 401);
        }

        $expire = Auth('api')->setToken($token)->getPayload()->get('exp');
        $token = 'Bearer '.$token;

        $user = Auth('api')->User();
        $user_id = $user->id;

        // all good so return the token
        return compact('token', 'expire', 'user_id');
    }

    /**
     * @return array
     * @throws ApiException
     */
    public function refreshToken()
    {
        try{
            $old_token = Auth('api')->getToken();
            $token = Auth('api')->refresh($old_token);
        }
        catch (TokenExpiredException $e) {
            throw new ApiException(ErrorCode::TOKEN_EXPIRE, trans("api.error.token_expire"), 401);
        } catch (JWTException $e) {
            throw new ApiException(ErrorCode::TOKEN_INVALID, trans("api.error.token_invalid"), 401);
        }

        $expire = Auth('api')->setToken($token)->getPayload()->get('exp');
        $token = 'Bearer '.$token;

        // all good so return the token
        return compact('token', 'expire');
    }

    private function guardStr()
    {
        return 'api';
    }

    /**
     * @return array
     * @throws ApiException
     */
    private function validateTokenInternal()
    {
        $valid = true;
        try{
            $token = Auth('api')->getToken();
            AuthUtil::checkOrFail($this->guardStr());
        }
        catch (TokenExpiredException $e) {
            $valid = false;
            try{
                $token = Auth('api')->refresh();
                Auth('api')->setToken($token);
                AuthUtil::checkOrFail($this->guardStr());
            }
            catch (TokenExpiredException $e){
                throw new ApiException(ErrorCode::TOKEN_EXPIRE, trans("api.error.token_expire"), 401);
            }
            catch (JWTException $e){
                throw new ApiException(ErrorCode::TOKEN_INVALID, trans("api.error.token_invalid"), 401);
            }
        } catch (JWTException $e) {
            throw new ApiException(ErrorCode::TOKEN_INVALID, trans("api.error.token_invalid"), 401);
        }

        $expire = Auth('api')->setToken($token)->getPayload()->get('exp');
        $token = 'Bearer '.$token;

        // all good so return the token
        return compact('valid', 'token', 'expire');
    }

    /**
     * @return array
     * @throws ApiException
     */
    public function validateToken()
    {
        $ret = $this->validateTokenInternal();

        return $ret;
    }

    /**
     * @return mixed
     */
    public function logout()
    {
        Auth('api')->invalidate();
        return true;
    }
}
