<?php

declare(strict_types=1);

namespace WooGuard\Health\Checks;

use WooGuard\Health\Check;
use WooGuard\Health\CheckResult;
use WooGuard\Health\Status;

final class StorePagesCheck implements Check
{
    public function run(): CheckResult
    {
        $cartId = wc_get_page_id('cart');
        $checkoutId = wc_get_page_id('checkout');

        $cartHealthy = $this->isPublishedPage($cartId);
        $checkoutHealthy = $this->isPublishedPage($checkoutId);
        $healthy = $cartHealthy && $checkoutHealthy;

        return new CheckResult(
            'store_pages',
            __('Cart & checkout pages', 'wooguard'),
            $healthy ? Status::Good : Status::Critical,
            $healthy
                ? __('Cart and checkout pages are configured and published.', 'wooguard')
                : __('Cart or checkout is missing or not published.', 'wooguard'),
            [
                'Cart' => $cartHealthy ? 'healthy' : 'missing/unpublished',
                'Checkout' => $checkoutHealthy ? 'healthy' : 'missing/unpublished',
            ]
        );
    }

    private function isPublishedPage(int $pageId): bool
    {
        return $pageId > 0 && get_post_status($pageId) === 'publish';
    }
}
