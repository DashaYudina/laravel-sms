<?php

namespace Yudina\LaravelSmsNotification\Transport;

use Exception;
use GuzzleHttp\Client;

abstract class SMS implements ISms
{
    protected abstract function createSenderUrl(string $msg, $phones);
    protected abstract function createCheckCostUrl(string $msg, $phones);
    protected abstract function createBalanceUrl();
    protected abstract function analyseSendMessageResponse($response);
    protected abstract function analyseGetBalanceResponse($response);
    protected abstract function analyseGetMessageCostResponse($response);

    /**
     * Send message to sms server.
     *
     * @param  string  $msg
     * @param  mixed  $phones
     *
     * @return bool
     */
    public function sendMessage(string $msg, $phones): bool
    {
        try {
            if (!$this->isPossibleSendMessages($msg, $phones)) {
                return false;
            }

            $method     = 'GET';
            $request    = $this->createSenderUrl($msg, $phones);
            $response   = $this->sendRequest($method, $request);

            return $this->analyseSendMessageResponse($response);
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * Get user balance from sms server.
     *
     *
     * @return float
     */
    public function getBalance(): float
    {
        try {
            $method     = 'GET';
            $request    = $this->createBalanceUrl();
            $response   = $this->sendRequest($method, $request);

            return $this->analyseGetBalanceResponse($response);
        } catch (Exception $exception) {
            return -1;
        }
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
        try {
            $method     = 'GET';
            $request    = $this->createCheckCostUrl($msg, $phones);
            $response   = $this->sendRequest($method, $request);

            return $this->analyseGetMessageCostResponse($response);
        } catch (Exception $exception) {
            return -1;
        }
    }

    /**
     * Generate activated code.
     *
     * @param  int  $length
     *
     * @return string
     */
    public function generateCode(int $length): string
    {
        $result = '';

        for($i = 0; $i < $length; $i++)
            $result .= mt_rand(0, 9);

        return $result;
    }

    /**
     * Send request to server.
     *
     * @param  string  $method
     * @param  string $request
     *
     * @return mixed
     */
    public function sendRequest(string $method, string $request)
    {
        try {
            $client     = new Client();
            $response   = $client->request($method, $request);

            return json_decode($response->getBody()->getContents());
        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     * Check if it is possible send messaged to sms server.
     *
     * @param  string  $msg
     * @param  mixed  $phones
     *
     * @return bool
     */
    private function isPossibleSendMessages(string $msg, $phones): bool
    {
        $balance    = $this->getBalance();
        $cost       = $this->getMessagesCost($msg, $phones);

        if ($cost == -1 || $balance == -1 || $cost > $balance) {
            return false;
        }

        return true;
    }
}
