<?php
// app/Providers/AppServiceProvider.php
namespace App\Providers;

use App\Services\CindiqueService;
use App\Services\CindiqueServiceInterface;
use App\Services\HoaService;
use App\Services\HoaServiceInterface;
use App\Services\MaintenancesService;
use App\Services\MaintenancesServiceInterface;
use App\Services\ResidenceService;
use App\Services\ResidenceServiceInterface;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ResidenceServiceInterface::class, ResidenceService::class);
        $this->app->singleton(HoaServiceInterface::class, HoaService::class);
        $this->app->singleton(MaintenancesServiceInterface::class, MaintenancesService::class);
        $this->app->singleton(CindiqueServiceInterface::class, CindiqueService::class);
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
