<?php

declare(strict_types=1);

namespace WooGuard\Health\Checks;

use WooGuard\Health\Check;
use WooGuard\Health\CheckResult;
use WooGuard\Health\Status;

final class PaymentGatewaysCheck implements Check
{
    public function run(): CheckResult
    {
        $manager = WC()->payment_gateways();
        $gateways = $manager->payment_gateways();
        $enabled = [];

        foreach ($gateways as $gateway) {
            if (($gateway->enabled ?? 'no') === 'yes') {
                $enabled[] = (string) $gateway->get_title();
            }
        }

        return new CheckResult(
            'payment_gateways',
            __('Payment gateways', 'wooguard'),
            $enabled !== [] ? Status::Good : Status::Warning,
            $enabled !== []
                ? sprintf(
                    /* translators: %d: enabled payment gateway count. */
                    _n('%d payment gateway is enabled.', '%d payment gateways are enabled.', count($enabled), 'wooguard'),
                    count($enabled)
                )
                : __('No payment gateway is currently enabled.', 'wooguard'),
            [
                'Enabled' => $enabled !== [] ? implode(', ', $enabled) : 'none',
                'Detected' => count($gateways),
            ]
        );
    }
}
