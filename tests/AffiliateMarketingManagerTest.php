<?php
namespace ItmoSoft\AffiliateMarketing\Tests;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;
use LogicException;
use ItmoSoft\AffiliateMarketing\AffiliateMarketingManager;
use PHPUnit\Framework\Attributes\Test;
class AffiliateTestOwner extends Model {
    protected $table='affiliate_test_owners'; protected $guarded=[]; public $timestamps=false;
}
class AffiliateMarketingManagerTest extends TestCase {
    protected function setUp(): void {
        parent::setUp();
        Schema::create('affiliate_test_owners', function (Blueprint $table): void { $table->id(); });
    }
    private function manager(): AffiliateMarketingManager { return $this->app->make(AffiliateMarketingManager::class); }
    #[Test] public function it_registers_an_affiliate_and_normalizes_code(): void {
        $owner=AffiliateTestOwner::query()->create(); $affiliate=$this->manager()->register($owner,'adeel2026',10);
        $this->assertSame('ADEEL2026',$affiliate->code); $this->assertSame($owner->getKey(),$affiliate->affiliateable->getKey());
    }
    #[Test] public function it_rejects_invalid_rates(): void {
        $owner=AffiliateTestOwner::query()->create(); $this->expectException(InvalidArgumentException::class); $this->manager()->register($owner,'BAD',101);
    }
    #[Test] public function it_rejects_empty_codes(): void {
        $owner=AffiliateTestOwner::query()->create(); $this->expectException(InvalidArgumentException::class); $this->manager()->register($owner,' ',10);
    }
    #[Test] public function it_records_conversion_and_commission_snapshot(): void {
        $owner=AffiliateTestOwner::query()->create(); $this->manager()->register($owner,'SAVE10',10);
        $conversion=$this->manager()->recordConversion('save10','ORDER-100',250,'usd');
        $this->assertSame('250.00',$conversion->amount); $this->assertSame('USD',$conversion->currency);
        $this->assertSame('25.00',$conversion->commission_amount); $this->assertSame('pending',$conversion->status);
    }
    #[Test] public function it_rejects_duplicate_conversion_references(): void {
        $owner=AffiliateTestOwner::query()->create(); $this->manager()->register($owner,'DUP',5);
        $this->manager()->recordConversion('DUP','ORDER-200',100,'USD');
        $this->expectException(\Illuminate\Database\QueryException::class); $this->manager()->recordConversion('DUP','ORDER-200',100,'USD');
    }
    #[Test] public function it_rejects_unknown_codes(): void {
        $this->expectException(InvalidArgumentException::class); $this->manager()->recordConversion('UNKNOWN','ORDER-300',10,'USD');
    }
    #[Test] public function it_approves_a_pending_conversion_only_once(): void {
        $owner=AffiliateTestOwner::query()->create(); $this->manager()->register($owner,'APPROVE',12.5);
        $conversion=$this->manager()->recordConversion('APPROVE','ORDER-400',80,'USD'); $this->manager()->approve($conversion);
        $this->assertSame('approved',$conversion->fresh()->status);
        $this->expectException(LogicException::class); $this->manager()->approve($conversion->fresh());
    }
    #[Test] public function it_rejects_pending_conversion_with_reason(): void {
        $owner=AffiliateTestOwner::query()->create(); $this->manager()->register($owner,'REJECT',12.5);
        $conversion=$this->manager()->recordConversion('REJECT','ORDER-500',80,'USD'); $this->manager()->reject($conversion,'Refunded');
        $this->assertSame('rejected',$conversion->fresh()->status); $this->assertSame('Refunded',$conversion->fresh()->rejection_reason);
    }
    #[Test] public function it_calculates_commission_from_snapshotted_rate(): void {
        $owner=AffiliateTestOwner::query()->create(); $this->manager()->register($owner,'CALC',7.5);
        $conversion=$this->manager()->recordConversion('CALC','ORDER-600',200,'USD');
        $this->assertSame('15.00',$this->manager()->calculateCommission($conversion));
    }
}
