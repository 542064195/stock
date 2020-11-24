<?php


namespace Liachange\Stock\Exceptions;

use InvalidArgumentException;

class StockDoesNotExist extends InvalidArgumentException
{
    public static function withSkuId(int $skuId, $warehouseId)
    {
        return new static("商品`{$skuId}`标示已存在仓库标示 `{$warehouseId}`中.");
    }
    public static function withId(int $id)
    {
        return new static("库存标示 `{$id}`不存在.");
    }
}