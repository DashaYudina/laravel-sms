<?php

namespace Yudina\LaravelSms\Exceptions;

use Exception;

class SmsProviderNotFound extends Exception
{
    public static function smsProviderNotFound(string $name)
    {
        return new static("Sms provider `{$name}` not found.");
    }
}
