<?php

declare(strict_types=1);

namespace WooGuard\Admin;

use WooGuard\Health\Check;
use WooGuard\Health\Checks\ActionSchedulerCheck;
use WooGuard\Health\Checks\CronCheck;
use WooGuard\Health\Checks\EnvironmentCheck;
use WooGuard\Health\Checks\PaymentGatewaysCheck;
use WooGuard\Health\Checks\StorePagesCheck;
use WooGuard\Health\CheckResult;
use WooGuard\Health\Status;

final class HealthPage
{
    public function register(): void
    {
        add_action('admin_menu', [$this, 'addMenu']);
    }

    public function addMenu(): void
    {
        add_submenu_page(
            'woocommerce',
            __('WooGuard Health', 'wooguard'),
            __('WooGuard', 'wooguard'),
            'manage_woocommerce',
            'wooguard',
            [$this, 'render']
        );
    }

    public function render(): void
    {
        if (! current_user_can('manage_woocommerce')) {
            wp_die(esc_html__('You do not have permission to view WooGuard.', 'wooguard'));
        }

        $results = $this->runChecks();
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('WooGuard Health', 'wooguard'); ?></h1>
            <p><?php echo esc_html__('A local snapshot of the store checks used by the WooGuard V0.1 prototype.', 'wooguard'); ?></p>

            <table class="widefat striped" style="max-width: 1100px; margin-top: 20px;">
                <thead>
                    <tr>
                        <th><?php echo esc_html__('Status', 'wooguard'); ?></th>
                        <th><?php echo esc_html__('Check', 'wooguard'); ?></th>
                        <th><?php echo esc_html__('Result', 'wooguard'); ?></th>
                        <th><?php echo esc_html__('Details', 'wooguard'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $result) : ?>
                        <tr>
                            <td><strong><?php echo esc_html($this->statusLabel($result->status)); ?></strong></td>
                            <td><?php echo esc_html($result->label); ?></td>
                            <td><?php echo esc_html($result->summary); ?></td>
                            <td><?php echo wp_kses_post($this->renderDetails($result)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    /**
     * @return list<CheckResult>
     */
    private function runChecks(): array
    {
        /** @var list<Check> $checks */
        $checks = [
            new EnvironmentCheck(),
            new StorePagesCheck(),
            new PaymentGatewaysCheck(),
            new CronCheck(),
            new ActionSchedulerCheck(),
        ];

        return array_map(
            static fn (Check $check): CheckResult => $check->run(),
            $checks
        );
    }

    private function statusLabel(Status $status): string
    {
        return match ($status) {
            Status::Good => '✅ ' . __('Healthy', 'wooguard'),
            Status::Warning => '⚠️ ' . __('Warning', 'wooguard'),
            Status::Critical => '❌ ' . __('Critical', 'wooguard'),
            Status::Info => 'ℹ️ ' . __('Info', 'wooguard'),
        };
    }

    private function renderDetails(CheckResult $result): string
    {
        if ($result->details === []) {
            return '—';
        }

        $items = [];

        foreach ($result->details as $label => $value) {
            $items[] = sprintf(
                '<strong>%s:</strong> %s',
                esc_html((string) $label),
                esc_html(is_bool($value) ? ($value ? 'true' : 'false') : (string) $value)
            );
        }

        return implode('<br>', $items);
    }
}
