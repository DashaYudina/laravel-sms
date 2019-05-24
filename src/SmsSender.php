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

    /**
     * Get user balance from sms server.
     *
     *
     * @return float
     */
    public function getBalance(): float
    {
        return $this->sms->getBalance();
    }

    /**
     * Get message cost from sms server.
     *
     * @param  string  $msg
     * @param  mixed  $phones
     *
     * @return float
     */
    public function getMessagesCost(string $msg, $phones): float
    {
        return $this->sms->getMessagesCost($msg, $phones);
    }
}
