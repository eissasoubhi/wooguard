<?php

declare(strict_types=1);

namespace WooGuard\Health\Checks;

use WooGuard\Health\Check;
use WooGuard\Health\CheckResult;
use WooGuard\Health\Status;

final class CronCheck implements Check
{
    public function run(): CheckResult
    {
        $disabled = defined('DISABLE_WP_CRON') && DISABLE_WP_CRON;

        return new CheckResult(
            'wp_cron',
            __('WP-Cron configuration', 'wooguard'),
            $disabled ? Status::Warning : Status::Good,
            $disabled
                ? __('WordPress cron spawning is disabled. Verify that a real system cron is configured.', 'wooguard')
                : __('WordPress cron spawning is enabled.', 'wooguard'),
            [
                'DISABLE_WP_CRON' => $disabled ? 'true' : 'false',
            ]
        );
    }
}
