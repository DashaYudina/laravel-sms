<?php

namespace Yudina\LaravelSms\Registry;

use Yudina\LaravelSms\Exceptions\SmsProviderNotFound;
use Yudina\LaravelSms\Transport\ISms;

class SmsRegistry
{
    private $providers;

    /**
     * Add new sms provider.
     *
     * @param  string  $key
     * @param  ISms  $sms
     *
     * @return void
     */
    public function add(string $key, ISms $sms): void
    {
        $this->providers[$key] = $sms;
    }

    /**
     * Get sms provider by driver.
     *
     * @param  string  $key
     *
     * @return ISms
     */
    public function get(string $key): ISms
    {
        if (array_key_exists($key, $this->providers)) {
            return $this->providers[$key];
        }

        throw SmsProviderNotFound::smsProviderNotFound($key);
    }
}
