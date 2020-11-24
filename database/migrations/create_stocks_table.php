<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('status')->default(0)->comment('状态');
            $table->unsignedBigInteger('warehouse_id')->default(0)->comment('仓库标示');
            $table->unsignedBigInteger('sku_id')->default(0)->comment('SKU标示');
            $table->unsignedBigInteger('unit_id')->default(0)->comment('库存单位');
            $table->string('name')->nullable()->comment('名称');
            $table->string('image_url')->nullable()->comment('主图片');
            $table->string('bar_code')->nullable()->comment('编码');
            $table->unsignedInteger('current_amount')->default(0)->comment('现货库存');
            $table->unsignedInteger('reserve_amount')->default(0)->comment('销售预占库存');
            $table->unsignedInteger('lock_amount')->default(0)->comment('锁定库存');
            $table->unsignedInteger('allot_reserve_amount')->default(0)->comment('调拨占用库存');
            $table->unsignedInteger('allot_amount')->default(0)->comment('调拨中库存');
            $table->unsignedInteger('virtual_amount')->default(0)->comment('虚库存');
            $table->softDeletes('deleted_at', 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}
