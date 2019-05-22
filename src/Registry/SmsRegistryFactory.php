<?php

namespace Yudina\LaravelSms\Registry;

use Yudina\LaravelSms\Factory\SmsFactory;

class SmsRegistryFactory
{
    private $smsFactory;

    public function __construct(SmsFactory $smsFactory)
    {
        $this->smsFactory = $smsFactory;

        if ($smsFactory->isEmpty()) {
            $this->smsFactory = $smsFactory->createDefault();
        }
    }

    /**
     * Create sms registry for all sms providers from config.
     *
     * @param  array  $config
     *
     * @return SmsRegistry
     */
    public function create(array $config): SmsRegistry
    {
        $registry = new SmsRegistry();

        foreach ($config as $key => $entry) {
            $registry->add($key, $this->smsFactory->init($key, $entry));
        }

        return $registry;
    }
}
