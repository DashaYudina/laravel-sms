<?php

namespace Yudina\LaravelSmsNotification\Transport;

use Exception;

class SmscRu extends SMS
{
    private $driver = 'smscru';
    private $login;
    private $password;
    private $url;
    private $sender;

    /**
     * Initialize sms provider.
     *
     * @param  array  $config
     *
     * @return ISms
     */
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
     * Get registered senders list from server.
     *
     *
     * @return mixed
     */
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

    /**
     * Add new sender for sms provider.
     *
     * @param  string  $sender
     * @param  string  $comment
     *
     * @return mixed
     */
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

    /**
     * Change sender name.
     *
     * @param  string  $sender
     * @param  string  $comment
     *
     * @return mixed
     */
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

    /**
     * Delete sender.
     *
     * @param  string  $sender
     * @param  string  $comment
     *
     * @return mixed
     */
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

    /**
     * Send a verification code for the sender's digital name.
     *
     * @param  string  $sender
     * @param  string  $comment
     *
     * @return mixed
     */
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

    /**
     * Verify the sender's digital name.
     *
     * @param  string  $sender
     * @param  string  $code
     *
     * @return mixed
     */
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
        return  "{$this->url}/sys/send.php?login={$this->login}&psw={$this->password}&phones={$phones}&mes={$msg}&fmt=3&sender={$this->sender}";
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
        return "{$this->url}/sys/send.php?login={$this->login}&psw={$this->password}&phones={$phones}&mes={$msg}&cost=1&fmt=3";
    }

    /**
     * Create url for get balance.
     *
     *
     * @return string
     */
    protected function createBalanceUrl()
    {
        return "{$this->url}/sys/balance.php?login={$this->login}&psw={$this->password}&fmt=3";
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
        if ($response == null || isset($response->error)) {
            return false;
        }

        return true;
    }

    /**
     * Analyse server response for get balance request.
     *
     * @param  string  $response
     *
     * @return mixed
     */
    protected function analyseGetBalanceResponse($response)
    {
        if ($response == null || isset($response->error) || !isset($response->balance)) {
            return -1;
        }

        return $response->balance;
    }

    /**
     * Analyse server response for get message cost request.
     *
     * @param  string  $response
     *
     * @return mixed
     */
    protected function analyseGetMessageCostResponse($response)
    {
        if ($response == null || isset($response->error) || !isset($response->cost)) {
            return -1;
        }

        return $response->cost;
    }

    /**
     * Create url for get senders list.
     *
     *
     * @return string
     */
    private function createSendersListUrl() {
        return "{$this->url}/sys/senders.php?get=1&login={$this->login}&psw={$this->password}&fmt=3";
    }

    /**
     * Create url for add new sender.
     *
     * @param  string  $sender
     * @param  string  $cmt
     *
     * @return string
     */
    private function createAddNewSenderUrl(string $sender, string $cmt) {
        return "{$this->url}/sys/senders.php?add=1&login={$this->login}&psw={$this->password}&sender={$sender}&cmt={$cmt}&fmt=3";
    }

    /**
     * Create url for change sender.
     *
     * @param  string  $sender
     * @param  string  $cmt
     *
     * @return string
     */
    private function createChangeSenderUrl(string $sender, string $cmt) {
        return "{$this->url}/sys/senders.php?chg=1&login={$this->login}&psw={$this->password}&sender={$sender}&cmt={$cmt}&fmt=3";
    }

    /**
     * Create url for delete sender.
     *
     * @param  string  $sender
     *
     * @return string
     */
    private function createDeleteSenderUrl(string $sender) {
        return "{$this->url}/sys/senders.php?del=1&login={$this->login}&psw={$this->password}&sender={$sender}&fmt=3";
    }

    /**
     * Create url for send sender code.
     *
     * @param  string  $sender
     *
     * @return string
     */
    private function createSenderSendCodeUrl(string $sender) {
        return "{$this->url}/sys/senders.php?send_code=1&login={$this->login}&psw={$this->password}&sender={$sender}&fmt=3";
    }

    /**
     * Create url for check sender code.
     *
     * @param  string  $sender
     * @param  mixed  $code
     *
     * @return string
     */
    private function createSenderCheckCodeUrl(string $sender, $code) {
        return "{$this->url}/sys/senders.php?check_code=1&login={$this->login}&psw={$this->password}&sender={$sender}&code={$code}&fmt=3";
    }
}
