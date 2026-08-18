<?php

declare(strict_types=1);

namespace WooGuard\Health\Checks;

use WooGuard\Health\Check;
use WooGuard\Health\CheckResult;
use WooGuard\Health\Status;

final class EnvironmentCheck implements Check
{
    public function run(): CheckResult
    {
        $wordpress = get_bloginfo('version');
        $woocommerce = defined('WC_VERSION') ? (string) WC_VERSION : 'not detected';

        return new CheckResult(
            'environment',
            __('Environment', 'wooguard'),
            defined('WC_VERSION') ? Status::Good : Status::Critical,
            defined('WC_VERSION')
                ? __('WordPress, PHP and WooCommerce are available.', 'wooguard')
                : __('WooCommerce could not be detected.', 'wooguard'),
            [
                'WordPress' => $wordpress,
                'PHP' => PHP_VERSION,
                'WooCommerce' => $woocommerce,
            ]
        );
    }
}
