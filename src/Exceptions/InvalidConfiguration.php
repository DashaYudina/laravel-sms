<?php

namespace Yudina\LaravelSms\Exceptions;

use Exception;

class InvalidConfiguration extends Exception
{
    public static function configurationNotSet()
    {
        return new static('In order to send notification you need to add credentials in the `sms-notification` key of `config.services`.');
    }
}
