<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responseSuccess($params = null)
    {
        if ($params == null)
        {
            $params = new \stdClass();
        }

        return response()->json($params);
    }

    public function responseSuccessWithObject($obj)
    {
        if ($obj)
        {
            if (!is_array($obj))
            {
                $obj = $obj->toArray();
            }
        }
        else
        {
            $obj = new \stdClass();
        }

        return response()->json($obj);
    }
}
