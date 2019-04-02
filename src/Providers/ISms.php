<?php

namespace Yudina\LaravelSmsNotification\Providers;


interface ISms
{
    public function create(array $config): ISms;
    public function sendMessage(string $msg, $phones): bool;
    public function getBalance(): float;
    public function getMessagesCost(string $msg, $phones): float;
    public function generateCode(int $length): string;
    public function isSupport(string $driver): bool;
}
