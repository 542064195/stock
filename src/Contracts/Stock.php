<?php


namespace Liachange\Stock\Contracts;


interface Stock
{
    /**
     * 查询库存详细
     * @param int $skuId SKU标示
     * @param $warehouseId 仓库标示
     * @return static
     */
    public static function findBySkuId(int $skuId, $warehouseId): self;

    /**
     * 权限库存详细
     * @param int $id 库存标示
     * @return static
     */
    public static function findById(int $id): self;

    /**
     * 添加库存
     * @param array $params 库存参数
     * @return static
     */
    public static function findOrCreate(array $params = []): self;

    /**
     * 自增
     * @param string $fieldName 自增字段名称
     * @param int $amount 自增数量
     * @param array $params 条件
     * @return int 影响的行数
     */
    public static function findOrIncrement(string $fieldName, int $amount, array $params): int;

    /**
     *自减
     * @param string $fieldName 自减字段名称
     * @param int $amount 自减数量
     * @param array $params 条件
     * @return int 影响的行数
     */
    public static function findOrDecrement(string $fieldName, int $amount, array $params): int;
}