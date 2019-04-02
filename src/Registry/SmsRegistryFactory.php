<?php

namespace Yudina\LaravelSmsNotification\Registry;


use Yudina\LaravelSmsNotification\Factory\SmsFactory;

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

    public function create(array $config): SmsRegistry
    {
        $registry = new SmsRegistry();

        foreach ($config as $key => $entry) {
            $registry->add($key, $this->smsFactory->init($key, $entry));
        }

        return $registry;
    }
}
