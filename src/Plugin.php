<?php

declare(strict_types=1);

namespace WooGuard;

use WooGuard\Admin\HealthPage;

final class Plugin
{
    public function boot(): void
    {
        if (! is_admin()) {
            return;
        }

        (new HealthPage())->register();
    }
}
