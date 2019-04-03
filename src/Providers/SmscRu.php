<?php

namespace Yudina\LaravelSmsNotification\Providers;

use Exception;

class SmscRu extends SMS
{
    private $driver = 'smscru';
    private $login;
    private $password;
    private $url;
    private $sender;

    public function create(array $config): ISms {
        foreach ($config as $key => $entry) {
            if ($key === 'login') {
                $this->login = $entry;
            } else if ($key === 'url') {
                $this->url = $entry;
            } else if ($key === 'password') {
                $this->password = $entry;
            } else if ($key === 'sender') {
                $this->sender = $entry;
            }
        }

        return $this;
    }

    public function isSupport(string $driver): bool
    {
        return $driver === $this->driver;
    }

    public function getRegisteredSendersList() {
        try {
            $method     = 'GET';
            $request    = $this->createSendersListUrl();
            $response   = $this->sendRequest($method, $request);

            if ($response == null || isset($response->error)) {
                return null;
            }

            return $response;
        } catch (Exception $exception) {
            return null;
        }
    }

    public function addNewSender(string $sender, string $comment) {
        try {
            $method     = 'GET';
            $request    = $this->createAddNewSenderUrl($sender, $comment);
            $response   = $this->sendRequest($method, $request);

            if ($response == null || isset($response->error) || !isset($response->sender)) {
                return null;
            }

            return $response->sender;
        } catch (Exception $exception) {
            return null;
        }
    }

    public function changeSender(string $sender, string $comment) {
        try {
            $method     = 'GET';
            $request    = $this->createChangeSenderUrl($sender, $comment);
            $response   = $this->sendRequest($method, $request);

            if ($response == null || isset($response->error)) {
                return null;
            }

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    public function deleteSender(string $sender, string $comment) {
        try {
            $method     = 'GET';
            $request    = $this->createDeleteSenderUrl($sender, $comment);
            $response   = $this->sendRequest($method, $request);

            if ($response == null || isset($response->error)) {
                return null;
            }

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    public function sendSenderCode(string $sender) {
        try {
            $method     = 'GET';
            $request    = $this->createSenderSendCodeUrl($sender);
            $response   = $this->sendRequest($method, $request);

            if ($response == null || isset($response->error)) {
                return false;
            }

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    public function checkSenderCode(string $sender, $code) {
        try {
            $method     = 'GET';
            $request    = $this->createSenderCheckCodeUrl($sender, $code);
            $response   = $this->sendRequest($method, $request);

            if ($response == null || isset($response->error)) {
                return false;
            }

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }


    protected function createSenderUrl(string $msg, $phones)
    {
        return  "{$this->url}/sys/send.php?login={$this->login}&psw={$this->password}&phones={$phones}&mes={$msg}&fmt=3&sender={$this->sender}";
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

    private function createSendersListUrl() {
        return "{$this->url}/sys/senders.php?get=1&login={$this->login}&psw={$this->password}&fmt=3";
    }

    private function createAddNewSenderUrl(string $sender, string $cmt) {
        return "{$this->url}/sys/senders.php?add=1&login={$this->login}&psw={$this->password}&sender={$sender}&cmt={$cmt}&fmt=3";
    }

    private function createChangeSenderUrl(string $sender, string $cmt) {
        return "{$this->url}/sys/senders.php?chg=1&login={$this->login}&psw={$this->password}&sender={$sender}&cmt={$cmt}&fmt=3";
    }

    private function createDeleteSenderUrl(string $sender, string $cmt) {
        return "{$this->url}/sys/senders.php?del=1&login={$this->login}&psw={$this->password}&sender={$sender}&fmt=3";
    }

    private function createSenderSendCodeUrl(string $sender) {
        return "{$this->url}/sys/senders.php?send_code=1&login={$this->login}&psw={$this->password}&sender={$sender}&fmt=3";
    }

    private function createSenderCheckCodeUrl(string $sender, $code) {
        return "{$this->url}/sys/senders.php?check_code=1&login={$this->login}&psw={$this->password}&sender={$sender}&code={$code}&fmt=3";
    }
}
