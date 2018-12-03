<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Api\VersionAndroid\VersionAndroidRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VersionAndroidController extends Controller
{
    private $versionAndroidRepo;

    /**
     * VersionAndroidController constructor.
     * @param $versionAndroidRepo
     */
    public function __construct(VersionAndroidRepository $versionAndroidRepo)
    {
        $this->versionAndroidRepo = $versionAndroidRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return $this->responseSuccessWithObject($this->versionAndroidRepo->getLatest());
    }
}
