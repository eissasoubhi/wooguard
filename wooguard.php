<?php
/**
 * Plugin Name: WooGuard
 * Plugin URI: https://github.com/eissasoubhi/wooguard
 * Description: Detects WooCommerce store health regressions before they become customer incidents.
 * Version: 0.1.0-dev
 * Requires at least: 6.5
 * Requires PHP: 8.1
 * Requires Plugins: woocommerce
 * Author: Aissa Soubhi
 * Developer: Aissa Soubhi
 * Text Domain: wooguard
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

define('WOOGUARD_VERSION', '0.1.0-dev');
define('WOOGUARD_FILE', __FILE__);
define('WOOGUARD_PATH', plugin_dir_path(__FILE__));

$autoload = WOOGUARD_PATH . 'vendor/autoload.php';

if (! is_readable($autoload)) {
    add_action('admin_notices', static function (): void {
        if (! current_user_can('activate_plugins')) {
            return;
        }

        echo '<div class="notice notice-error"><p>';
        echo esc_html__('WooGuard dependencies are missing. Run composer install in the WooGuard plugin directory.', 'wooguard');
        echo '</p></div>';
    });

    return;
}

require $autoload;

add_action('before_woocommerce_init', static function (): void {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
            'custom_order_tables',
            WOOGUARD_FILE,
            true
        );
    }
});

add_action('plugins_loaded', static function (): void {
    if (! class_exists('WooCommerce')) {
        return;
    }

    (new \WooGuard\Plugin())->boot();
}, 20);
