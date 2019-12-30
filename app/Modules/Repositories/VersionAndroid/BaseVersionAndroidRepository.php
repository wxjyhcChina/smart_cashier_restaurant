<?php

namespace App\Modules\Repositories\VersionAndroid;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\VersionAndroid\VersionAndroid;
use App\Modules\Repositories\BaseRepository;
use Illuminate\Support\Facades\Log;

/**
 * Class BaseVersionAndroidRepository.
 */
class BaseVersionAndroidRepository extends BaseRepository
{

    /**
     * Associated Repository Model.
     */
    const MODEL = VersionAndroid::class;


    /**
     * @return mixed
     */
    public function getLatest()
    {
        return $this->query()->OrderBy('version_code', 'desc')->first();
    }

    /**
     * @param $input
     * @return VersionAndroid
     * @throws ApiException
     */
    public function create($input)
    {
        $versionAndroid = $this->createVersionAndroidStub($input);

        if ($versionAndroid->save())
        {
            return $versionAndroid;
        }

        throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.versionAndroid.create_error'));
    }


    /**
     * @param VersionAndroid $versionAndroid
     * @param $input
     * @return bool
     * @throws ApiException
     */
    public function update(VersionAndroid $versionAndroid, $input)
    {
        Log::info("update param:" . json_encode($input));

        if ($versionAndroid->update($input)) {
            return true;
        }

        throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.versionAndroid.update_error'));
    }

    /**
     * @param $input
     * @return VersionAndroid
     */
    private function createVersionAndroidStub($input)
    {
        $versionAndroid = new VersionAndroid();
        $versionAndroid->forced = isset($input['forced']) ? 1 : 0;
        $versionAndroid->version_name = $input['version_name'];
        $versionAndroid->version_code = $input['version_code'];
        $versionAndroid->update_info = $input['update_info'];
        $versionAndroid->download_url = $input['download_url'];

        return $versionAndroid;
    }

    /**
     * @param VersionAndroid $versionAndroid
     * @throws \Exception
     */
    public function delete(VersionAndroid $versionAndroid)
    {
        $versionAndroid->delete();
    }
}
