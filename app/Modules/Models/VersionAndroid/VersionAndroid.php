<?php

namespace App\Modules\Models\VersionAndroid;

use App\Modules\Models\VersionAndroid\Traits\Attribute\VersionAndroidAttribute;
use Illuminate\Database\Eloquent\Model;

class VersionAndroid extends Model
{
    use VersionAndroidAttribute;

    protected $table = 'version_android';

    protected $fillable = ['id', 'version_name', 'version_code', 'forced', 'update_info', 'download_url'];

    public function getForcedAttribute($value)
    {
        if ($value)
        {
            return true;
        }

        return false;
    }

    public function getDownloadUrlAttribute($value)
    {
        return config('constants.qiniu.download_bucket_url').$value;
    }
}