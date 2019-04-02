<?php

namespace Yudina\LaravelSmsNotification\Registry;


use Yudina\LaravelSmsNotification\Exceptions\SmsProviderNotFound;
use Yudina\LaravelSmsNotification\Providers\ISms;

class SmsRegistry
{
    private $providers;

    public function add(string $key, ISms $sms): void
    {
        $this->providers[$key] = $sms;
    }

    public function get(string $key): ISms
    {
        if (array_key_exists($key, $this->providers)) {
            return $this->providers[$key];
        }

        throw SmsProviderNotFound::smsProviderNotFound($key);
    }
}
