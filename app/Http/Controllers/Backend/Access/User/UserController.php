<?php

namespace App\Http\Controllers\Backend\Access\User;

use App\Access\Model\User\User;
use App\Http\Controllers\Controller;
use App\Access\Repository\Role\RoleRepository;
use App\Access\Repository\User\UserRepository;
use App\Http\Requests\Backend\Access\User\StoreUserRequest;
use App\Http\Requests\Backend\Access\User\ManageUserRequest;
use App\Http\Requests\Backend\Access\User\UpdateUserRequest;
use App\Modules\Models\Shop\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
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
        //Log::info("create");

        $user = Auth::user();
        $shops = Shop::where('restaurant_id', $user->restaurant_id)->get();
        $shops = $this->getSelectArray($shops);

        $restaurantCode = $this->getRestaurantCode();

        return view('backend.access.create')
            ->withShops($shops)
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
        //Log::info("store");
        $user = Auth::user();
        $restaurantCode = $this->getRestaurantCode();

        $data = $request->only(
            'first_name',
            'last_name',
            'username',
            'password',
            'status',
            'shop_id'
        );
        //Log::info("store".json_encode($data));
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
        $shops = Shop::where('restaurant_id', $user->restaurant_id)->get();
        $shops = $this->getSelectArray($shops);

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
            ->withShops($shops)
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
            'confirmed',
            'shop_id'
        );

        $data['username'] = $data['username'].'@'.$this->getRestaurantCode();
        $data['status'] = $user->status;
        $firstUser = User::where('restaurant_id', $user->restaurant_id)->orderBy('id', 'asc')->first();
        //Log::info("update".json_encode($data));
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

    /**
     * @param $models
     * @return array
     */
    private function getSelectArray($models)
    {
        $selectArray = [null => '请选择'];
        foreach ($models as $model)
        {
            $selectArray[$model->id] = $model->name;
        }

        return $selectArray;
    }
}
