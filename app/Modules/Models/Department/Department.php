<?php

namespace App\Modules\Models\Department;

use App\Modules\Models\Department\Traits\Attribute\DepartmentAttribute;
use App\Modules\Models\Department\Traits\Relationship\DepartmentRelationship;
use Illuminate\Database\Eloquent\Model;


class Department extends Model
{
    use DepartmentAttribute, DepartmentRelationship;

    protected $fillable = ['code', 'name', 'enabled'];
}