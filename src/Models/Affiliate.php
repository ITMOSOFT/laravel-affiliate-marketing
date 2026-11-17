<?php
namespace ItmoSoft\AffiliateMarketing\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Affiliate extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['commission_rate' => 'decimal:4', 'is_active' => 'boolean']; }
    public function getTable(): string { return config('affiliate-marketing.tables.affiliates', 'affiliates'); }
    public function affiliateable(): MorphTo { return $this->morphTo(); }
    public function conversions(): HasMany { return $this->hasMany(AffiliateConversion::class); }
}
