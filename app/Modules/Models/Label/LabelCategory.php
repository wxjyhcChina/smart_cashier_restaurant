<?php

namespace App\Modules\Models\Label;

use App\Modules\Models\Label\Traits\Attribute\LabelCategoryAttribute;
use App\Modules\Models\Label\Traits\Relationship\LabelCategoryRelationship;
use Illuminate\Database\Eloquent\Model;

class LabelCategory extends Model
{
    use LabelCategoryAttribute, LabelCategoryRelationship;

    protected $fillable = ['name', 'image'];

    protected $hidden = ['pivot'];

    public function getImageAttribute($value)
    {
        if ($value == '' || strpos($value, 'http') === 0)
        {
            return $value;
        }

        return config('constants.qiniu.image_bucket_url').$value;
    }
}