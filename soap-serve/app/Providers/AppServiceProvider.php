<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Soap\SoapProvider;
use SoapFault;
use SoapServer;

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
        try {
            $server = new SoapServer(null, [
                "uri" => "http://localhost:8001"
            ]);

            $server->setClass(SoapProvider::class);
            $server->handle();
        } catch (SoapFault $error) {
            throw $error;
        }
    }
}
