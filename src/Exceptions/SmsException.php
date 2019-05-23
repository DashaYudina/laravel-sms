<?php

namespace Yudina\LaravelSms\Exceptions;

use Exception;

class SmsException extends Exception
{
    public static function getExceptionInfo(int $errorCode)
    {
        if ($errorCode === 1) {
            return self::parametersError();
        }

        if ($errorCode === 2) {
            return self::loginError();
        }

        if ($errorCode === 3) {
            return self::insufficientFundsError();
        }

        if ($errorCode === 4) {
            return self::ipAddressIsBlocked();
        }

        if ($errorCode === 5) {
            return self::invalidDateFormat();
        }

        if ($errorCode === 6) {
            return self::messageIsDenied();
        }

        if ($errorCode === 7) {
            return self::invalidPhoneFormat();
        }

        if ($errorCode === 8) {
            return self::messageCouldNotBeDelivered();
        }

        if ($errorCode === 9) {
            return self::requestNumberError();
        }
    }

    public static function serviceRespondedWithError(Exception $exception)
    {
        return new static("Sms service responded with an error '{$exception->getCode()}: {$exception->getMessage()}'");
    }

    public static function parametersError()
    {
        return new static("Error in parameters.");
    }

    public static function loginError()
    {
        return new static("Invalid login or password.");
    }

    public static function insufficientFundsError()
    {
        return new static("Insufficient funds.");
    }

    public static function ipAddressIsBlocked()
    {
        return new static("IP address is temporarily blocked due to frequent errors in requests.");
    }

    public static function invalidDateFormat()
    {
        return new static("Invalid date format.");
    }

    public static function messageIsDenied()
    {
        return new static("Message is denied.");
    }

    public static function invalidPhoneFormat()
    {
        return new static("Invalid phone number format.");
    }

    public static function messageCouldNotBeDelivered()
    {
        return new static("The message to the specified number could not be delivered.");
    }

    public static function requestNumberError()
    {
        return new static("Sending more than one identical request for sending an SMS message or sending more than 15 any requests at the same time.");
    }
}
