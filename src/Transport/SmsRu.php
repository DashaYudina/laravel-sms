<?php

namespace Yudina\LaravelSms\Transport;

use Yudina\LaravelSms\Exceptions\SmsException;

class SmsRu extends SMS
{
    private $driver = 'smsru';
    private $url    = 'https://sms.ru';
    private $api_id;

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
     * @param  mixed  $sender
     *
     * @return string
     */
    protected function createSenderUrl(string $msg, $phones, $sender = null)
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
     * @return mixed
     *
     * @throws SmsException
     */
    protected function analyseSendMessageResponse($response)
    {
        if (isset($response->status_code) && $response->status_code !== 100) {
            throw SmsException::responseError($response->status_code, $response->status_text);
        }

        return true;
    }

    /**
     * Analyse server response for get balance request.
     *
     * @param  string  $response
     *
     * @return mixed
     *
     * @throws SmsException
     */
    protected function analyseGetBalanceResponse($response)
    {
        if (isset($response->status) && $response->status === 'ERROR') {
            throw SmsException::responseError($response->status_code, $response->status_text);
        }

        if (isset($response->balance)) {
            return $response->balance;
        }

        throw SmsException::responseParametersError('balance');
    }

    /**
     * Analyse server response for get message cost request.
     *
     * @param  string  $response
     *
     * @return mixed
     *
     * @throws SmsException
     */
    protected function analyseGetMessageCostResponse($response)
    {
        if (isset($response->status) && $response->status === 'ERROR') {
            throw SmsException::responseError($response->status_code, $response->status_text);
        }

        if (isset($response->total_cost)) {
            return $response->total_cost;
        }

        throw SmsException::responseParametersError('total_cost');
    }
}
