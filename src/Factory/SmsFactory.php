<?php

namespace Yudina\LaravelSmsNotification\Factory;


use Yudina\LaravelSmsNotification\Exceptions\SmsProviderNotFound;
use Yudina\LaravelSmsNotification\Providers\ISms;
use Yudina\LaravelSmsNotification\Providers\SmsRu;

class SmsFactory
{
    private $providers;

    public function __construct(ISms ...$providers)
    {
        $this->providers = $providers;
    }

    public function isEmpty() {
        return count($this->providers) === 0;
    }

    public function createDefault(): SmsFactory
    {
        return new SmsFactory(new SmsRu());
    }

    public function create(string $driver): ISms
    {
        return $this->getProviderByDriver($driver);
    }

    public function init(string $key, array $config): ISms
    {
        return $this->getProviderByDriver($key)->create($config);
    }

    private function getProviderByDriver(string $driver): ISms
    {
        foreach ($this->providers as $provider) {
            if ($provider->isSupport($driver)) {
                return $provider;
            }
        }

        throw SmsProviderNotFound::smsProviderNotFound($driver);
    }
}
