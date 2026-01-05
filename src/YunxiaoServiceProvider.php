<?php
declare(strict_types=1);

namespace Lingb\AliyunYunxiaoDevops;

use Illuminate\Support\ServiceProvider;

final class YunxiaoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/yunxiao.php', 'yunxiao');

        $this->app->singleton(YunxiaoClient::class, function ($app) {
            $config = new YunxiaoConfig(
                accessToken: config('yunxiao.personal_access_token'),
                organizationId: config('yunxiao.organization_id'),
                domain: config('yunxiao.domain')
            );

            return new YunxiaoClient($config);
        });

        $this->app->alias(YunxiaoClient::class, 'yunxiao');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/yunxiao.php' => config_path('yunxiao.php'),
            ], 'yunxiao-config');
        }
    }
}
