<?php

namespace Yudina\LaravelSmsNotification\Providers;

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

    public function generateCode(int $length): string
    {
        $result = '';

        for($i = 0; $i < $length; $i++)
            $result .= mt_rand(0, 9);

        return $result;
    }

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
