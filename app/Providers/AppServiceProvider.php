<?php

namespace App\Providers;

use App\Models\Lead;
use Laravel\Cashier\Cashier;
use Illuminate\Support\ServiceProvider;
use Spatie\QueryBuilder\QueryBuilderRequest;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Set spatie query builder value delimiter
        QueryBuilderRequest::setArrayValueDelimiter('|');

        // Cashier billable model
        Cashier::useCustomerModel(Lead::class);
    }
}
