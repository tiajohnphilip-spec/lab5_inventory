<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\InventoryTransaction;
use App\Observers\InventoryTransactionObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        InventoryTransaction::observe(
            InventoryTransactionObserver::class
        );
    }
}