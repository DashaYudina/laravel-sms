<?php

namespace Yudina\LaravelSmsNotification;

use Illuminate\Notifications\Notification;
use Yudina\LaravelSmsNotification\Exceptions\CouldNotSendNotification;

class SmsSenderChannel
{
    private $smsSender;

    public function __construct(SmsSender $smsSender)
    {
        $this->smsSender = $smsSender;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     *
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSms($notifiable);

        if (is_string($message)) {
            $message = SmsSenderMessage::create($message);
        }

        $phones = $this->getRecipients($notifiable, $notification);

        if ($phones == null) {
            throw CouldNotSendNotification::phoneListIsEmpty();
        }

        $message->setRecipients($phones);

        $this->smsSender->send($message->body, $message->recipients);
    }

    /**
     * Gets a list of phones from the given notifiable.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     *
     * @return string[]
     */
    protected function getRecipients($notifiable, Notification $notification)
    {
        $to = $notifiable->routeNotificationFor('sms', $notification);

        if (empty($to)) {
            return null;
        }

        return is_array($to) ? $to : [$to];
    }
}
