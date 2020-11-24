<?php


namespace Liachange\Stock;

use Liachange\Stock\Contracts\Stock;
use Illuminate\Support\Collection;

class StockRegistrar
{
    /** @var string */
    protected $stockClass;

    /** @var \Illuminate\Support\Collection */
    protected $stocks;

    public function __construct()
    {
        $this->stockClass = config('stock.models.stock');
    }

    /**
     * @return Liachange\Stock\Contracts\Stock
     */
    public function getStockClass(): Stock
    {
        return app($this->stockClass);
    }

    public function getStocks(array $params = []): Collection
    {
        if ($this->stocks === null) {
            //缓存扩展
            $this->stocks = $this->getStockClass()->get();
        }
        $stocks = clone $this->stocks;

        foreach ($params as $attr => $value) {
            $stocks = $stocks->where($attr, $value);
        }

        return $stocks;
    }

    public function setStockClass($stockClass)
    {
        $this->stockClass = $stockClass;

        return $this;
    }
}