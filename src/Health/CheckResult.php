<?php

declare(strict_types=1);

namespace WooGuard\Health;

final class CheckResult
{
    /**
     * @param array<string, string|int|float|bool> $details
     */
    public function __construct(
        public readonly string $id,
        public readonly string $label,
        public readonly Status $status,
        public readonly string $summary,
        public readonly array $details = []
    ) {
    }
}
