# ITMOSOFT Laravel Affiliate Marketing

Affiliate program management for Laravel 12 and 13: register affiliates, attribute confirmed conversions, calculate commissions, and approve or reject commission records.

## Requirements
- PHP 8.3+
- Laravel 12 or 13

## Installation
```bash
composer require itmosoft/laravel-affiliate-marketing
php artisan vendor:publish --tag=itmosoft-affiliate-marketing-config
php artisan migrate
```

## Register an affiliate
```php
use ItmoSoft\AffiliateMarketing\Facades\AffiliateMarketing;

$affiliate = AffiliateMarketing::register($user, 'ADEEL2026', 10);
```
The first argument is any Eloquent model. Codes are normalized to uppercase and must be unique. Commission rates are percentages from 0 to 100.

## Record a conversion
```php
$conversion = AffiliateMarketing::recordConversion(
    code: 'ADEEL2026',
    reference: 'ORDER-10025',
    amount: 250.00,
    currency: 'USD',
);
```
Your application is responsible for capturing the affiliate code and attributing the order. Conversion references are unique to prevent duplicate commission records.

## Review a conversion
```php
AffiliateMarketing::approve($conversion);
// or
AffiliateMarketing::reject($conversion, 'Order refunded');
```
The commission amount and rate are snapshotted when the conversion is recorded.

## API
- `register(Model $affiliateable, string $code, float $commissionRate): Affiliate`
- `findByCode(string $code): ?Affiliate`
- `recordConversion(string $code, string $reference, int|float|string $amount, string $currency): AffiliateConversion`
- `calculateCommission(AffiliateConversion $conversion): string`
- `approve(AffiliateConversion $conversion): AffiliateConversion`
- `reject(AffiliateConversion $conversion, ?string $reason = null): AffiliateConversion`
- `conversionsFor(Affiliate $affiliate): Builder`

## Configuration
Publish `config/affiliate-marketing.php` to customize table names and default currency.

## Boundaries
- Percentage commissions only.
- No cookie/session tracking, click attribution, first/last-touch attribution, payouts, payment gateway, tax calculation, chargeback automation, fraud detection, dashboard, or multi-tier commissions.
- No payout processing; use a separate payout workflow.
- Does not replace `laravel-referrals`, `laravel-loyalty`, `laravel-vendor-payouts`, or `laravel-campaign-tracking`.
- Applications must enforce their own authorization and fraud controls.

## Testing
```bash
composer install
composer test
```

MIT License. See LICENSE.
