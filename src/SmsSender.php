<?php

namespace Yudina\LaravelSms;

use Yudina\LaravelSms\Transport\ISms;

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
     * @param  mixed  $phones
     * @param  string  $msg
     * @param  mixed  $sender
     *
     * @return bool
     */
    public function send($phones, string $msg, $sender = null)
    {
        return $this->sms->sendMessage($msg, $phones, $sender);
    }
}
