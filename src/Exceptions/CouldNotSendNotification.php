<?php

namespace Yudina\LaravelSmsNotification\Exceptions;

use Exception;

class CouldNotSendNotification extends Exception
{
    public static function serviceRespondedWithAnError(Exception $exception)
    {
        return new static("Sms service responded with an error '{$exception->getCode()}: {$exception->getMessage()}'");
    }

    public static function phoneListIsEmpty()
    {
        return new static("Phone list is empty.");
    }
}
