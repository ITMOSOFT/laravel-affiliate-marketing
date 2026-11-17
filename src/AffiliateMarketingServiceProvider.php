<?php
namespace ItmoSoft\AffiliateMarketing;
use Illuminate\Support\ServiceProvider;
class AffiliateMarketingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/affiliate-marketing.php', 'affiliate-marketing');
        $this->app->singleton(AffiliateMarketingManager::class, fn () => new AffiliateMarketingManager());
        $this->app->alias(AffiliateMarketingManager::class, 'affiliate-marketing');
    }
    public function boot(): void
    {
        $this->publishes([__DIR__.'/../config/affiliate-marketing.php' => config_path('affiliate-marketing.php')], 'itmosoft-affiliate-marketing-config');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
