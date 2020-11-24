<?php


namespace Liachange\Stock;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Liachange\Stock\Contracts\Stock as StockContract;

class StockServiceProvider extends ServiceProvider
{
    public function boot(StockRegistrar $stockLoader, Filesystem $filesystem)
    {
        if (function_exists('config_path')) { // function not available and 'publish' not relevant in Lumen
            $this->publishes([
                __DIR__.'/../config/stock.php' => config_path('stock.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../database/migrations/create_stocks_table.php' => $this->getMigrationFileName($filesystem),
            ], 'migrations');
        }
        $this->app->singleton(StockRegistrar::class, function ($app) use ($stockLoader) {
            return $stockLoader;
        });
        $this->registerModelBindings();
    }

    protected function getMigrationFileName(Filesystem $filesystem): string
    {
        $timestamp = date('Y_m_d_His');

        return Collection::make($this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem) {
                return $filesystem->glob($path . '*_create_stocks_table.php');
            })->push($this->app->databasePath() . "/migrations/{$timestamp}_create_stocks_table.php")
            ->first();
    }

    protected function registerModelBindings()
    {
        $config = $this->app->config['stock.models'];

        if (!$config) {
            return;
        }
        $this->app->bind(StockContract::class, $config['stock']);
    }
}