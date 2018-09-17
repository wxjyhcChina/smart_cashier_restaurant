<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responseSuccess($params = array())
    {
        $responseArray = array_merge($params, ["error_code"=>0]);
        return response()->json($responseArray);
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
            $obj = array();
        }

        $obj['error_code'] = 0;

        return response()->json($obj);
    }
}
