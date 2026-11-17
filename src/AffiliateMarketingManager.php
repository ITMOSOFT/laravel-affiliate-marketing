<?php
namespace ItmoSoft\AffiliateMarketing;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use LogicException;
use ItmoSoft\AffiliateMarketing\Models\Affiliate;
use ItmoSoft\AffiliateMarketing\Models\AffiliateConversion;
class AffiliateMarketingManager
{
    public function register(Model $affiliateable, string $code, float $commissionRate): Affiliate
    {
        if (trim($code) === '') throw new InvalidArgumentException('Affiliate code cannot be empty.');
        if (!is_finite($commissionRate) || $commissionRate < 0 || $commissionRate > 100) throw new InvalidArgumentException('Commission rate must be between 0 and 100.');
        $affiliate = new Affiliate();
        $affiliate->affiliateable()->associate($affiliateable);
        $affiliate->code = strtoupper(trim($code));
        $affiliate->commission_rate = $commissionRate;
        $affiliate->is_active = true;
        $affiliate->save();
        return $affiliate;
    }
    public function findByCode(string $code): ?Affiliate
    {
        return Affiliate::query()->where('code', strtoupper(trim($code)))->where('is_active', true)->first();
    }
    public function recordConversion(string $code, string $reference, int|float|string $amount, string $currency): AffiliateConversion
    {
        $affiliate = $this->findByCode($code);
        if (!$affiliate) throw new InvalidArgumentException("Active affiliate code [{$code}] was not found.");
        if (trim($reference) === '') throw new InvalidArgumentException('Conversion reference cannot be empty.');
        if (!is_numeric($amount) || !is_finite((float)$amount) || (float)$amount < 0) throw new InvalidArgumentException('Amount must be a finite non-negative number.');
        $currency = strtoupper(trim($currency));
        if (!preg_match('/^[A-Z]{3}$/', $currency)) throw new InvalidArgumentException('Currency must be a three-letter code.');
        $commission = round((float)$amount * (float)$affiliate->commission_rate / 100, 2);
        return AffiliateConversion::query()->create([
            'affiliate_id' => $affiliate->getKey(),
            'reference' => trim($reference),
            'amount' => number_format((float)$amount, 2, '.', ''),
            'currency' => $currency,
            'commission_rate' => $affiliate->commission_rate,
            'commission_amount' => number_format($commission, 2, '.', ''),
            'status' => 'pending',
        ]);
    }
    public function calculateCommission(AffiliateConversion $conversion): string
    {
        return number_format(round((float)$conversion->amount * (float)$conversion->commission_rate / 100, 2), 2, '.', '');
    }
    public function approve(AffiliateConversion $conversion): AffiliateConversion
    {
        if ($conversion->status !== 'pending') throw new LogicException('Only pending conversions can be approved.');
        $conversion->status = 'approved';
        $conversion->approved_at = now();
        $conversion->rejection_reason = null;
        $conversion->save();
        return $conversion->refresh();
    }
    public function reject(AffiliateConversion $conversion, ?string $reason = null): AffiliateConversion
    {
        if ($conversion->status !== 'pending') throw new LogicException('Only pending conversions can be rejected.');
        $conversion->status = 'rejected';
        $conversion->rejection_reason = $reason;
        $conversion->save();
        return $conversion->refresh();
    }
    public function conversionsFor(Affiliate $affiliate): Builder
    {
        return $affiliate->conversions()->getQuery();
    }
}
