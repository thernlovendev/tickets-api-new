<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('integer_or_nullable', function ($attribute, $value, $parameters, $validator) {
            if ($value === null) {
                return true;
            }
            return is_numeric($value) && floor($value) == $value;
        });
    }
}
