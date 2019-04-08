<?php

namespace Yudina\LaravelSmsNotification;

use Yudina\LaravelSmsNotification\Transport\ISms;

class SmsSender
{
    protected $sms;

    public function __construct(ISms $sms)
    {
        $this->sms = $sms;
    }

    /**
     * Send the given messages to selected phones.
     *
     * @param  string  $msg
     * @param  mixed  $phones
     *
     * @return bool
     */
    public function send(string $msg, $phones)
    {
        return $this->sms->sendMessage($msg, $phones);
    }
}
