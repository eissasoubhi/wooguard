<?php

declare(strict_types=1);

namespace WooGuard\Health;

enum Status: string
{
    case Good = 'good';
    case Warning = 'warning';
    case Critical = 'critical';
    case Info = 'info';
}
