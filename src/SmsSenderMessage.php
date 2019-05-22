<?php

namespace Yudina\LaravelSms;

class SmsSenderMessage
{
    public $body;
    public $originator;
    public $recipients;
    public $reference;

    public static function create($body = '')
    {
        return new static($body);
    }

    public function __construct($body = '')
    {
        if (!empty($body)) {
            $this->body = trim($body);
        }
    }

    /**
     * Set sms body.
     *
     * @param  mixed  $body
     *
     * @return SmsSenderMessage
     */
    public function setBody($body)
    {
        $this->body = trim($body);

        return $this;
    }

    /**
     * Set sms originator.
     *
     * @param  mixed  $originator
     *
     * @return SmsSenderMessage
     */
    public function setOriginator($originator)
    {
        $this->originator = $originator;

        return $this;
    }

    /**
     * Set sms recipients.
     *
     * @param  mixed  $recipients
     *
     * @return SmsSenderMessage
     */
    public function setRecipients($recipients)
    {
        if (is_array($recipients)) {
            $recipients = implode(',', $recipients);
        }

        $this->recipients = $recipients;

        return $this;
    }

    /**
     * Set reference.
     *
     * @param  mixed  $reference
     *
     * @return SmsSenderMessage
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Set sms datacoding.
     *
     * @param  mixed  $datacoding
     *
     * @return SmsSenderMessage
     */
    public function setDatacoding($datacoding)
    {
        $this->datacoding = $datacoding;

        return $this;
    }

    /**
     * Convert to json.
     *
     *
     * @return mixed
     */
    public function toJson()
    {
        return json_encode($this);
    }
}
