<?php

declare(strict_types=1);

namespace WooGuard\Health\Checks;

use Throwable;
use WooGuard\Health\Check;
use WooGuard\Health\CheckResult;
use WooGuard\Health\Status;

final class ActionSchedulerCheck implements Check
{
    private const RESULT_LIMIT = 100;
    private const OVERDUE_GRACE_SECONDS = 300;

    public function run(): CheckResult
    {
        if (! function_exists('as_get_scheduled_actions')) {
            return new CheckResult(
                'action_scheduler',
                __('Action Scheduler', 'wooguard'),
                Status::Warning,
                __('Action Scheduler API is not available.', 'wooguard')
            );
        }

        try {
            $failed = as_get_scheduled_actions(
                [
                    'status' => 'failed',
                    'per_page' => self::RESULT_LIMIT,
                ],
                'ids'
            );

            $overdue = as_get_scheduled_actions(
                [
                    'status' => 'pending',
                    'date' => time() - self::OVERDUE_GRACE_SECONDS,
                    'date_compare' => '<=',
                    'per_page' => self::RESULT_LIMIT,
                ],
                'ids'
            );
        } catch (Throwable $exception) {
            return new CheckResult(
                'action_scheduler',
                __('Action Scheduler', 'wooguard'),
                Status::Critical,
                __('WooGuard could not inspect scheduled actions.', 'wooguard'),
                [
                    'Error' => $exception->getMessage(),
                ]
            );
        }

        $failedCount = is_array($failed) ? count($failed) : 0;
        $overdueCount = is_array($overdue) ? count($overdue) : 0;
        $healthy = $failedCount === 0 && $overdueCount === 0;

        return new CheckResult(
            'action_scheduler',
            __('Action Scheduler', 'wooguard'),
            $healthy ? Status::Good : Status::Warning,
            $healthy
                ? __('No failed or overdue scheduled actions detected.', 'wooguard')
                : __('Failed or overdue scheduled actions need attention.', 'wooguard'),
            [
                'Failed' => $this->formatCount($failedCount),
                'Overdue >5 min' => $this->formatCount($overdueCount),
            ]
        );
    }

    private function formatCount(int $count): string|int
    {
        return $count >= self::RESULT_LIMIT ? self::RESULT_LIMIT . '+' : $count;
    }
}
