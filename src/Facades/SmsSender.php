<?php


namespace Yudina\LaravelSms\Facades;

use Yudina\LaravelSms\SmsSender as Sender;
use Illuminate\Support\Facades\Facade;

class SmsSender extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Sender::class;
    }
}
