<?php

namespace App\Http\Controllers\Backend\Department;

use App\Exceptions\GeneralException;
use App\Http\Requests\Backend\Department\ManageDepartmentRequest;
use App\Http\Requests\Backend\Department\StoreDepartmentRequest;
use App\Modules\Models\Department\Department;
use App\Repositories\Backend\Department\DepartmentRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    /**
     * @var DepartmentRepository
     */
    private $departmentRepo;

    /**
     * DepartmentController constructor.
     * @param $departmentRepo
     */
    public function __construct(DepartmentRepository $departmentRepo)
    {
        $this->departmentRepo = $departmentRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  ManageDepartmentRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(ManageDepartmentRequest $request)
    {
        //
        return view('backend.department.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  ManageDepartmentRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(ManageDepartmentRequest $request)
    {
        //
        return view('backend.department.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreDepartmentRequest  $request
     * @return \Illuminate\Http\Response
     * @throws GeneralException
     */
    public function store(StoreDepartmentRequest $request)
    {
        //
        $input = $request->all();
        $input['restaurant_id'] = Auth::User()->restaurant_id;

        $this->departmentRepo->create($input);

        return redirect()->route('admin.department.index')->withFlashSuccess(trans('alerts.backend.card.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Department $department
     * @param  ManageDepartmentRequest $request
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department, ManageDepartmentRequest $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Department $department
     * @param  ManageDepartmentRequest $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department, ManageDepartmentRequest $request)
    {
        //
        return view('backend.department.edit')->withDepartment($department);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Department $department
     * @param  StoreDepartmentRequest $request
     * @return \Illuminate\Http\Response
     * @throws GeneralException
     */
    public function update(Department $department, StoreDepartmentRequest $request)
    {
        //
        $this->departmentRepo->update($department, $request->all());

        return redirect()->route('admin.department.index')->withFlashSuccess(trans('alerts.backend.department.created'));
    }

    /**
     * @param Department $department
     * @param $status
     * @param ManageDepartmentRequest $request
     * @return mixed
     * @throws GeneralException
     */
    public function mark(Department $department, $status, ManageDepartmentRequest $request)
    {
        $this->departmentRepo->mark($department, $status);

        return redirect()->back()->withFlashSuccess(trans('alerts.backend.department.updated'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
