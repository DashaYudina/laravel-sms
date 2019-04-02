<?php

namespace Yudina\LaravelSmsNotification\Providers;

use Exception;
use GuzzleHttp\Client;


class SmsRu implements ISms
{
    private $driver = 'smsru';
    private $api_id;
    private $url;

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

    public function sendMessage(string $msg, $phones): bool
    {
        try {
            if (!$this->isPossibleSendMessages($msg, $phones)) {
                return false;
            }

            $method     = 'GET';
            $request    = $this->createSenderUrl($msg, $phones);
            $response   = $this->sendRequest($method, $request);

            if ($response == null || $response->status_code != 100) {
                return false;
            }

            return true;
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

            if ($response == null || !isset($response->balance)) {
                return -1;
            }

            return $response->balance;
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

            if ($response == null || !isset($response->total_cost)) {
                return -1;
            }

            return $response->total_cost;
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

    public function isSupport(string $driver): bool
    {
        return $driver === $this->driver;
    }

    private function sendRequest(string $method, string $request)
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

    private function createSenderUrl(string $msg, $phones)
    {
        return  "{$this->url}/sms/send?api_id={$this->api_id}&to={$phones}&msg={$msg}&json=1";
    }

    private function createCheckCostUrl(string $msg, $phones)
    {
        return "{$this->url}/sms/cost?api_id={$this->api_id}&to={$phones}&msg={$msg}&json=1";
    }

    private function createBalanceUrl()
    {
        return "{$this->url}/my/balance?api_id={$this->api_id}&json=1";
    }
}
