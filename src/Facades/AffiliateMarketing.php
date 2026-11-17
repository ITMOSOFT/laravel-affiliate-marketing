<?php
namespace ItmoSoft\AffiliateMarketing\Facades;
use Illuminate\Support\Facades\Facade;
/**
 * @method static \ItmoSoft\AffiliateMarketing\Models\Affiliate register(\Illuminate\Database\Eloquent\Model $affiliateable, string $code, float $commissionRate)
 * @method static ?\ItmoSoft\AffiliateMarketing\Models\Affiliate findByCode(string $code)
 * @method static \ItmoSoft\AffiliateMarketing\Models\AffiliateConversion recordConversion(string $code, string $reference, int|float|string $amount, string $currency)
 * @method static string calculateCommission(\ItmoSoft\AffiliateMarketing\Models\AffiliateConversion $conversion)
 * @method static \ItmoSoft\AffiliateMarketing\Models\AffiliateConversion approve(\ItmoSoft\AffiliateMarketing\Models\AffiliateConversion $conversion)
 * @method static \ItmoSoft\AffiliateMarketing\Models\AffiliateConversion reject(\ItmoSoft\AffiliateMarketing\Models\AffiliateConversion $conversion, ?string $reason = null)
 * @method static \Illuminate\Database\Eloquent\Builder conversionsFor(\ItmoSoft\AffiliateMarketing\Models\Affiliate $affiliate)
 */
class AffiliateMarketing extends Facade
{
    protected static function getFacadeAccessor(): string { return 'affiliate-marketing'; }
}
