<?php

namespace Yudina\LaravelSmsNotification;

use Illuminate\Support\ServiceProvider;
use Yudina\LaravelSmsNotification\Exceptions\InvalidConfiguration;
use Yudina\LaravelSmsNotification\Registry\SmsRegistry;
use Yudina\LaravelSmsNotification\Registry\SmsRegistryFactory;
use Yudina\LaravelSmsNotification\Factory\SmsFactory;

class SmsSenderServiceProvider extends ServiceProvider
{
    public function register() {
        $this->mergeConfigFrom($this->configPath(), 'sms-notification');

        $this->app->bind(SmsRegistry::class, function () {
            return $this->createSmsRegistryFactory();
        });

        $this->app->bind(SmsSender::class, function($app) {
            $config             = config('sms-notification');
            $defaultSmsProvider = $config['default'];

            if (is_null($config) || is_null($defaultSmsProvider)) {
                throw InvalidConfiguration::configurationNotSet();
            }

            return $app->get(SmsRegistry::class)->get($defaultSmsProvider);
        });
    }

    public function boot()
    {
        $this->publishes([$this->configPath() => config_path('sms-notification.php')]);
    }

    /**
     * Create path to config.
     *
     *
     * @return string
     */
    protected function configPath()
    {
        return __DIR__ . '/../config/sms-notification.php';
    }

    /**
     * Create sms registry factory.
     *
     *
     * @return SmsRegistry
     */
    private function createSmsRegistryFactory(): SmsRegistry {
        $config = config('sms-notification');

        if (is_null($config)) {
            throw InvalidConfiguration::configurationNotSet();
        }

        $registryFactory = new SmsRegistryFactory(new SmsFactory());

        return $registryFactory->create($config['providers']);
    }
}
