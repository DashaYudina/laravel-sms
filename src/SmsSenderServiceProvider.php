<?php

namespace Yudina\LaravelSms;

use Illuminate\Support\ServiceProvider;
use Yudina\LaravelSms\Exceptions\InvalidConfiguration;
use Yudina\LaravelSms\Registry\SmsRegistry;
use Yudina\LaravelSms\Registry\SmsRegistryFactory;
use Yudina\LaravelSms\Factory\SmsFactory;

class SmsSenderServiceProvider extends ServiceProvider
{
    public function register() {
        $this->mergeConfigFrom($this->configPath(), 'sms');

        $this->app->bind(SmsRegistry::class, function () {
            return $this->createSmsRegistryFactory();
        });

        $this->app->bind(SmsSender::class, function($app) {
            $config             = config('sms');
            $defaultSmsProvider = $config['default'];

            if (is_null($config) || is_null($defaultSmsProvider)) {
                throw InvalidConfiguration::configurationNotSet();
            }

            return new SmsSender($app->get(SmsRegistry::class)->get($defaultSmsProvider));
        });
    }

    public function boot()
    {
        $this->publishes([$this->configPath() => config_path('sms.php')]);
    }

    /**
     * Create path to config.
     *
     *
     * @return string
     */
    protected function configPath()
    {
        return __DIR__ . '/../config/sms.php';
    }

    /**
     * Create sms registry factory.
     *
     *
     * @return SmsRegistry
     */
    private function createSmsRegistryFactory(): SmsRegistry {
        $config = config('sms');

        if (is_null($config)) {
            throw InvalidConfiguration::configurationNotSet();
        }

        $registryFactory = new SmsRegistryFactory(new SmsFactory());

        return $registryFactory->create($config['providers']);
    }
}
