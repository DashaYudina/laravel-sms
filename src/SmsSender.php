<?php

namespace Yudina\LaravelSmsNotification;


use Yudina\LaravelSmsNotification\Providers\ISms;

class SmsSender
{
    protected $sms;

    public function __construct(ISms $sms)
    {
        $this->sms = $sms;
    }

    public function send(string $msg, $phones)
    {
        return $this->sms->sendMessage($msg, $phones);
    }
}
