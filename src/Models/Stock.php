<?php


namespace Liachange\Stock\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Liachange\Stock\Contracts\Stock as StockContract;
use Liachange\Stock\StockRegistrar;
use Liachange\Stock\Exceptions\StockDoesNotExist;
use Liachange\Stock\Exceptions\StockDecrementException;
use Liachange\Stock\Exceptions\StockIncrementException;

class Stock extends Model implements StockContract
{
    protected $guarded = ['id'];

    /**
     * 添加库存信息
     * @param array $attributes 库存数组
     * @return mixed 库存详细
     */
    public static function create(array $attributes)
    {
        if (static::where('sku_id', $attributes['sku_id'])->where('warehouse_id', $attributes['warehouse_id'])->first()) {
            throw StockDoesNotExist::withSkuId($attributes['sku_id'], $attributes['warehouse_id']);
        }
        return static::query()->create($attributes);
    }

    /**
     *根据库存自增标示查询
     * @param int $id 库存自增标示
     * @return StockContract 库存详细
     *
     */
    public static function findById(int $id): StockContract
    {
        $stock = static::getStocks(['id' => $id])->first();

        if (!$stock) {
            throw  StockDoesNotExist::withId($id);
        }

        return $stock;
    }

    /**
     * 根据商品SKU和出库/门店标示查询
     * @param int $skuId
     * @param $warehouseId 仓库标示
     * @return StockContract
     */
    public static function findBySkuId(int $skuId, $warehouseId): StockContract
    {
        $stock = static::getStocks(['sku_id' => $skuId, 'warehouse_id' => $warehouseId])->first();

        if (!$stock) {
            throw StockDoesNotExist::withSkuId($skuId, $warehouseId);
        }

        return $stock;
    }

    /**
     * 添加库存
     * @param array $params 库存参数
     * @return StockContract
     */
    public static function findOrCreate(array $params = []): StockContract
    {
        $stock = static::getStocks(['sku_id' => $params['sku_id'], 'warehouse_id' => $params['warehouse_id']])->first();

        if (!$stock) {
            return static::query()->create($params);
        }

        return $stock;
    }

    /**
     * 自增
     * @param string $fieldName 自增字段名称
     * @param int $amount 自增数量
     * @param array $params 条件
     * @return int 影响的行数
     * @throws StockIncrementException
     */
    public static function findOrIncrement(string $fieldName, int $amount, array $params): int
    {
        if ($amount <= 0) {
            throw new StockIncrementException('自增数量不正确！');
        }
        $arrWhere = array();
        foreach ($params as $key => $val) {
            $arrWhere[] = [$key, $val];
        }
        $rows = static::where($arrWhere)->increment($fieldName, $amount);
        if (!$rows) {
            throw new StockIncrementException('库存数据更新失败！');
        }
        return $rows;
    }

    /**
     * 自减
     * @param string $fieldName 自减字段名称
     * @param int $amount 自减数量
     * @param array $params 条件
     * @return int 影响的行数
     * @throws StockDecrementException
     */
    public static function findOrDecrement(string $fieldName, int $amount, array $params): int
    {
        if ($amount <= 0) {
            throw new StockDecrementException('自减数量不正确！');
        }
        $arrWhere = array();
        foreach ($params as $key => $val) {
            $arrWhere[] = [$key, $val];
        }
        $rows = static::where($arrWhere)->where($fieldName, '>=', $amount)->decrement($fieldName, $amount);
        if (!$rows) {
            throw new StockDecrementException('库存数据更新失败！');
        }
        return $rows;
    }

    /**
     * 模型读取
     * @param array $params 参数
     * @return Collection
     */
    protected static function getStocks(array $params = []): Collection
    {
        return app(StockRegistrar::class)
            ->setStockClass(static::class)
            ->getStocks($params);
    }
}