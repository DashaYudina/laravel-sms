<?php

namespace Yudina\LaravelSmsNotification\Transport;

class SmsRu extends SMS
{
    private $driver = 'smsru';
    private $api_id;
    private $url;

    /**
     * Initialize sms provider.
     *
     * @param  array  $config
     *
     * @return ISms
     */
    public function create(array $config): ISms {
        foreach ($config as $key => $entry) {
            if ($key === 'api_id') {
                $this->api_id = $entry;
            } else if ($key === 'url') {
                $this->url = $entry;
            }
        }

        return $this;
    }

    /**
     * Support selected sms provider.
     *
     * @param  string  $driver
     *
     * @return bool
     */
    public function isSupport(string $driver): bool
    {
        return $driver === $this->driver;
    }

    /**
     * Create sender url.
     *
     * @param  string  $msg
     * @param  mixed  $phones
     *
     * @return string
     */
    protected function createSenderUrl(string $msg, $phones)
    {
        // $msg = iconv("windows-1251", "utf-8", $msg);

        return  "{$this->url}/sms/send?api_id={$this->api_id}&to={$phones}&msg=" . urlencode($msg) . "&json=1";
    }

    /**
     * Create url for check message cost.
     *
     * @param  string  $msg
     * @param  mixed  $phones
     *
     * @return string
     */
    protected function createCheckCostUrl(string $msg, $phones)
    {
        return "{$this->url}/sms/cost?api_id={$this->api_id}&to={$phones}&msg=" . urlencode($msg) . "&json=1";
    }

    /**
     * Create url for get balance.
     *
     *
     * @return string
     */
    protected function createBalanceUrl()
    {
        return "{$this->url}/my/balance?api_id={$this->api_id}&json=1";
    }

    /**
     * Analyse server response for send message request.
     *
     * @param  string  $response
     *
     * @return bool
     */
    protected function analyseSendMessageResponse($response)
    {
        if ($response == null || $response->status_code != 100) {
            return false;
        }

        return true;
    }

    /**
     * Analyse server response for get balance request.
     *
     * @param  string  $response
     *
     * @return bool
     */
    protected function analyseGetBalanceResponse($response)
    {
        if ($response == null || !isset($response->balance)) {
            return -1;
        }

        return $response->balance;
    }

    /**
     * Analyse server response for get message cost request.
     *
     * @param  string  $response
     *
     * @return bool
     */
    protected function analyseGetMessageCostResponse($response)
    {
        if ($response == null || !isset($response->total_cost)) {
            return -1;
        }

        return $response->total_cost;
    }
}
