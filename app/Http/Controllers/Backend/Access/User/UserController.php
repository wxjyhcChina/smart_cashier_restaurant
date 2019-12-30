<?php

namespace App\Http\Controllers\Backend\Access\User;

use App\Access\Model\User\User;
use App\Http\Controllers\Controller;
use App\Access\Repository\Role\RoleRepository;
use App\Access\Repository\User\UserRepository;
use App\Http\Requests\Backend\Access\User\StoreUserRequest;
use App\Http\Requests\Backend\Access\User\ManageUserRequest;
use App\Http\Requests\Backend\Access\User\UpdateUserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Class UserController.
 */
class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @var RoleRepository
     */
    protected $roles;

    /**
     * @param UserRepository $users
     * @param RoleRepository $roles
     */
    public function __construct(UserRepository $users, RoleRepository $roles)
    {
        $this->users = $users;
        $this->roles = $roles;
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ManageUserRequest $request)
    {
        return view('backend.access.index');
    }

    private function getRestaurantCode()
    {
        $username = Auth::user()->username;
        $restaurantCode = substr($username, strrpos($username, '@')+1);

        return $restaurantCode;
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function create(ManageUserRequest $request)
    {
        $restaurantCode = $this->getRestaurantCode();

        return view('backend.access.create')
            ->withRoles($this->roles->getAll())
            ->withRestaurantCode($restaurantCode);
    }

    /**
     * @param StoreUserRequest $request
     *
     * @return mixed
     */
    public function store(StoreUserRequest $request)
    {
        $user = Auth::user();
        $restaurantCode = $this->getRestaurantCode();

        $data = $request->only(
            'first_name',
            'last_name',
            'username',
            'password',
            'status'
        );

        $data['status'] = 1;
        $data['restaurant_id'] = $user->restaurant_id;
        $data['username'] = $data['username'].'@'.$restaurantCode;

        Validator::make($data, [
            'username' => Rule::unique(config('access.users_table'))
        ])->validate();

        $this->users->create(
            [
                'data' => $data,
                'roles' => $request->only('assignees_roles'),
            ]);



        return redirect()->route('admin.access.user.index')->withFlashSuccess(trans('alerts.backend.users.created'));
    }

    /**
     * @param User              $user
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function show(User $user, ManageUserRequest $request)
    {
        return view('backend.access.show')
            ->withUser($user);
    }

    /**
     * @param User              $user
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function edit(User $user, ManageUserRequest $request)
    {
        $username = $user->username;
        $pos = strpos($username, '@');
        $realUsername = substr($username, 0, $pos);
        $restaurantCode = substr($username, $pos+1);
        $firstUser = User::where('restaurant_id', $user->restaurant_id)->orderBy('id', 'asc')->first();

        $isFirst = false;
        if ($user->id == $firstUser->id)
        {
            $isFirst = true;
        }

        return view('backend.access.edit')
            ->withUser($user)
            ->withUserRoles($user->roles->pluck('id')->all())
            ->withRoles($this->roles->getAll())
            ->withUsername($realUsername)
            ->withRestaurantCode($restaurantCode)
            ->withFirstUser($isFirst);
    }

    /**
     * @param User $user
     * @param UpdateUserRequest $request
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function update(User $user, UpdateUserRequest $request)
    {

        $data = $request->only(
            'first_name',
            'last_name',
            'username',
            'confirmed'
        );

        $data['username'] = $data['username'].'@'.$this->getRestaurantCode();
        $data['status'] = $user->status;
        $firstUser = User::where('restaurant_id', $user->restaurant_id)->orderBy('id', 'asc')->first();

        if ($user->id == $firstUser->id)
        {
            $this->users->update($user,
                [
                    'data' => $data,
                ]);
        }
        else
        {
            $this->users->update($user,
                [
                    'data' => $data,
                    'roles' => $request->only('assignees_roles'),
                ]);
        }


        return redirect()->route('admin.access.user.index')->withFlashSuccess(trans('alerts.backend.users.updated'));
    }

    /**
     * @param User              $user
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function destroy(User $user, ManageUserRequest $request)
    {
        $this->users->delete($user);

        return redirect()->route('admin.access.user.deleted')->withFlashSuccess(trans('alerts.backend.users.deleted'));
    }
}
