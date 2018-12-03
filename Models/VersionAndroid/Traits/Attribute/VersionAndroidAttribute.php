<?php

namespace App\Modules\Models\VersionAndroid\Traits\Attribute;

/**
 * Class VersionAndroidAttribute
 * @package App\Modules\Models\VersionAndroid\Traits\Attribute
 */
trait VersionAndroidAttribute
{
    /**
     * @return string
     */
//    public function getEditButtonAttribute()
//    {
//        return '<a href="' . route('admin.androidVersion.edit', $this) . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.edit') . '"></i></a> ';
//    }

    /**
     * @return string
     */
    public function getDeleteButtonAttribute()
    {
        return '<a href="' . route('admin.versionAndroid.destroy', $this) . '" class="btn btn-xs btn-danger" data-method="delete"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></a>';
    }

    /**
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return
//            $this->getEditButtonAttribute() .
            $this->getDeleteButtonAttribute();
    }
}
