<h1 align="center"> 库存 </h1>

<p align="center"> 商品库存扩展包.</p>


## 使用 Composer 安装:

```shell
$ composer require liachange/stock -vvv
生成数据库迁移文件
php artisan vendor:publish --provider="Liachange\Stock\StockServiceProvider" --tag="migrations"
生成配置信息
php artisan vendor:publish --provider="Liachange\Stock\StockServiceProvider" --tag="config"
```
## 目录结构
    stock/
    ├── config             # 配置文件
    ├── database           # 迁移文件
    ├── .editorconfig      # 编辑器配置文件，比如缩进大小、换行模式等
    ├── .gitattributes     # git 配置文件，可以设计导出时忽略文件等
    ├── .gitignore         # git 忽略文件配置列表
    ├── .php_cs            # PHP-CS-Fixer 配置文件
    ├── README.md    
    ├── composer.json
    ├── phpunit.xml.dist
    ├── src
        ├── Contracts       #接口
        ├── Exceptions      #异常处理
        ├── Models          #模型
    │   └── .gitkeep
    └── tests
        └── .gitkeep
## 在 Laravel 中使用

    use Liachange\Stock\Models\Stock;

    //减少库存
    $array = ['sku_id' => 1, 'warehouse_id' => 1];
    $i = Stock::findOrDecrement('lock_amount', 1, $array);

    //增加库存
    $array = ['sku_id' => 1, 'warehouse_id' => 1];
    $i = Stock::findOrIncrement('lock_amount', 1, $array);

    //根据仓库/门店添加商品库存
    $array = ['status' => 0, 'warehouse_id' => 1, 'sku_id' => 2, 'unit_id' => 1, 'name' => 1, 'image_url' => 1, 'bar_code' => 1, 'current_amount' => 1, 'reserve_amount' => 1, 'lock_amount' => 1, 'allot_reserve_amount' => 1, 'allot_amount' => 1, 'virtual_amount' => 1];
    $i = Stock::findOrCreate($array);