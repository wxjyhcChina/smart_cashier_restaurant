<?php

namespace App\Http\Controllers\Backend\Access\User;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Access\Repository\User\UserRepository;
use App\Http\Requests\Backend\Access\User\ManageUserRequest;

/**
 * Class UserTableController.
 */
class UserTableController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function __invoke(ManageUserRequest $request)
    {
        return DataTables::of($this->users->getForDataTable($request->get('status'), $request->get('trashed')))
            ->escapeColumns(['first_name', 'last_name', 'username'])
            ->addColumn('roles', function ($user) {
                return $user->roles->count() ?
                    implode('<br/>', $user->roles->pluck('name')->toArray()) :
                    trans('labels.general.none');
            })
            ->addColumn('actions', function ($user) {
                return $user->action_buttons;
            })
            ->setRowClass(function ($user) {
                return ! $user->isConfirmed() && config('access.users.requires_approval') ? 'danger' : '';
            })
            ->make(true);
    }
}
