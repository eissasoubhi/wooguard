<?php

declare(strict_types=1);

namespace WooGuard\Health;

interface Check
{
    public function run(): CheckResult;
}
