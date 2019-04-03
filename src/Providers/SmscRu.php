<?php

namespace Yudina\LaravelSmsNotification\Providers;


class SmscRu extends SMS
{
    private $driver = 'smscru';
    private $login;
    private $password;
    private $url;

    public function create(array $config): ISms {
        foreach ($config as $key => $entry) {
            if ($key === 'login') {
                $this->login = $entry;
            } else if ($key === 'url') {
                $this->url = $entry;
            } else if ($key === 'password') {
                $this->password = $entry;
            }
        }

        return $this;
    }

    public function isSupport(string $driver): bool
    {
        return $driver === $this->driver;
    }

    protected function createSenderUrl(string $msg, $phones)
    {
        return  "{$this->url}/sys/send.php?login={$this->login}&psw={$this->password}&phones={$phones}&mes={$msg}&fmt=3";
    }

    protected function createCheckCostUrl(string $msg, $phones)
    {
        return "{$this->url}/sys/send.php?login={$this->login}&psw={$this->password}&phones={$phones}&mes={$msg}&cost=1&fmt=3";
    }

    protected function createBalanceUrl()
    {
        return "{$this->url}/sys/balance.php?login={$this->login}&psw={$this->password}&fmt=3";
    }

    protected function analyseSendMessageResponse($response)
    {
        if ($response == null || isset($response->error)) {
            return false;
        }

        return true;
    }

    protected function analyseGetBalanceResponse($response)
    {
        if ($response == null || isset($response->error) || !isset($response->balance)) {
            return -1;
        }

        return $response->balance;
    }

    protected function analyseGetMessageCostResponse($response)
    {
        if ($response == null || isset($response->error) || !isset($response->cost)) {
            return -1;
        }

        return $response->cost;
    }
}
