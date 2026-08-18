# WooGuard

WooGuard is an early-stage WooCommerce reliability plugin focused on answering one practical question:

> Did a WordPress/WooCommerce update break something important in the store?

## Current stage

WooGuard is currently in **prototype validation (V0.1)**. The goal is not to build the SaaS yet. The first milestone is a small WordPress plugin that can inspect a WooCommerce store, save a health baseline, and later compare the store against that baseline.

## V0.1 scope

The first prototype will focus on these checks:

- WordPress/PHP environment
- WooCommerce availability/version
- Cart page
- Checkout page
- Enabled payment gateways
- WP-Cron availability
- Action Scheduler failed/past-due actions
- Baseline snapshot
- Before/after comparison

Out of scope for V0.1:

- Cloud/SaaS backend
- Accounts or billing
- AI diagnosis
- Automatic external monitoring
- Multi-store dashboard
- Slack/Telegram integrations

## Development workflow

The repository contains the WooGuard plugin only. WordPress and WooCommerce are development/runtime dependencies and are not committed here.

Recommended local workflow:

1. Create a local WordPress + WooCommerce store (LocalWP is fine).
2. Clone this repository somewhere such as `~/Projects/wooguard`.
3. Run Composer install once the prototype branch provides `composer.json`.
4. Symlink the repository into the local site's `wp-content/plugins/wooguard` directory.
5. Activate **WooGuard** from WordPress Admin.
6. Pull updates from GitHub and refresh the local site to test them.

Example symlink on macOS:

```bash
ln -s ~/Projects/wooguard "/path/to/local-site/app/public/wp-content/plugins/wooguard"
```

## Product validation sequence

1. Build the technical prototype.
2. Intentionally reproduce store failures locally and verify detection.
3. Show the prototype to WooCommerce freelancers/agencies.
4. Validate the workflow and willingness to pay.
5. Only after validation, build continuous cloud monitoring and paid agency plans.

## Status

Pre-release. Do not use on production stores yet.
