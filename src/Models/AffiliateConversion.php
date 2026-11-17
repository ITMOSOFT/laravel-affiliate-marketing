<?php
namespace ItmoSoft\AffiliateMarketing\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class AffiliateConversion extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['amount' => 'decimal:2', 'commission_rate' => 'decimal:4', 'commission_amount' => 'decimal:2', 'approved_at' => 'immutable_datetime']; }
    public function getTable(): string { return config('affiliate-marketing.tables.conversions', 'affiliate_conversions'); }
    public function affiliate(): BelongsTo { return $this->belongsTo(Affiliate::class); }
}
