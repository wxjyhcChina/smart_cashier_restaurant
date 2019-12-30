<?php

namespace App\Modules\Models\Device;

use App\Modules\Models\Device\Traits\Attribute\DeviceAttribute;
use App\Modules\Models\Device\Traits\Relationship\DeviceRelationship;
use Illuminate\Database\Eloquent\Model;


class Device extends Model
{
    use DeviceAttribute, DeviceRelationship;

    protected $fillable = ['restaurant_id', 'serial_id', 'enabled'];
}