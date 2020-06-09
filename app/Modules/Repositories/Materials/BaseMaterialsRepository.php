<?php

namespace App\Modules\Repositories\Materials;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\Materials\Materials;
use App\Modules\Models\Shop\Shop;
use App\Modules\Models\Stocks\Stocks;
use App\Modules\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class BaseShopRepository.
 */
class BaseMaterialsRepository extends BaseRepository
{

    /**
     * Associated Repository Model.
     */
    const MODEL = Materials::class;


    public function getByRestaurantQuery($restaurant_id)
    {
        return $this->query()->where('restaurant_id', $restaurant_id);
    }

    public function getByShopQuery($shop_id)
    {
        return $this->query()->where('shop_id', $shop_id);
    }

    public function getInfoById($material_id)
    {
        return $this->query()->where('id', $material_id);
    }

    /**
     * @param Materials $material
     * @param $input
     * @return Materials
     * @throws ApiException
     */
    public function update(Materials $material, $input)
    {
        Log::info("material update param:".json_encode($input));

        try
        {
            DB::beginTransaction();
            $material->update($input);

            DB::commit();

            return $material;
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.customer.update_error'));
        }
    }
    public function shopExist($name,$shop_id, $updatedMaterial = null)
    {
        $materialQuery = Materials::where('name', $name)->where('shop_id', $shop_id);

        if ($updatedMaterial != null)
        {
            $materialQuery->where('id', '<>', $updatedMaterial->id);
        }

        if ($materialQuery->first() != null)
        {
            throw new ApiException(ErrorCode::MATERIAL_ALREADY_EXIST, trans('exceptions.backend.materials.already_exist'));
        }
    }

    //新增材料信息
    public function createMaterials($input){
        $this->shopExist($input['name'],$input['shop_id']);
        $material=$this->createMaterialStub($input);
        try
        {
            DB::beginTransaction();

            Log::info("create materials param:".json_encode($material));
            //新增库存表count:0
            $stock=$this->createStockStub($material);
            Log::info("create stock param:".json_encode($stock));
            $stock->save();
            DB::commit();

            return $material;
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.materials.create_error'));
        }
    }

    private function createMaterialStub($input){
        $material=new Materials();
        $material->name=$input['name'];
        $material->restaurant_id=$input['restaurant_id'];
        $material->shop_id=$input['shop_id'];
        $material->main_supplier=$input['main_supplier'];
        $material->save();
        return $material;
    }

    private function createStockStub($material){
        $stock=new Stocks();
        $stock->material_id=$material->id;
        $stock->restaurant_id=$material->restaurant_id;
        $stock->shop_id=$material->shop_id;
        $stock->count=0;
        return $stock;
    }

}
