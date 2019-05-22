<?php

namespace Yudina\LaravelSms\Factory;

use Yudina\LaravelSms\Exceptions\SmsProviderNotFound;
use Yudina\LaravelSms\Transport\ISms;
use Yudina\LaravelSms\Transport\SmscRu;
use Yudina\LaravelSms\Transport\SmsRu;

class SmsFactory
{
    private $providers;

    public function __construct(ISms ...$providers)
    {
        $this->providers = $providers;
    }

    /**
     * Check if factory is empty.
     *
     * @return bool
     */
    public function isEmpty() {
        return count($this->providers) === 0;
    }

    /**
     * Create default factory.
     *
     *
     * @return SmsFactory
     */
    public function createDefault(): SmsFactory
    {
        return new SmsFactory(new SmsRu(), new SmscRu());
    }

    /**
     * Create sms provider for selected driver.
     *
     * @param  string  $driver
     *
     * @return ISms
     */
    public function create(string $driver): ISms
    {
        return $this->getProviderByDriver($driver);
    }

    /**
     * Initialize sms provider.
     *
     * @param  string  $key
     * @param  array  $config
     *
     * @return ISms
     */
    public function init(string $key, array $config): ISms
    {
        return $this->getProviderByDriver($key)->create($config);
    }

    /**
     * Get sms provider by selected driver.
     *
     * @param  string  $driver
     *
     * @return ISms
     */
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
