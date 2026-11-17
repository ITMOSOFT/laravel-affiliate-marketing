<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create(config('affiliate-marketing.tables.affiliates', 'affiliates'), function (Blueprint $table): void {
            $table->id(); $table->morphs('affiliateable'); $table->string('code', 64)->unique();
            $table->decimal('commission_rate', 7, 4); $table->boolean('is_active')->default(true); $table->timestamps();
        });
        Schema::create(config('affiliate-marketing.tables.conversions', 'affiliate_conversions'), function (Blueprint $table): void {
            $table->id(); $table->foreignId('affiliate_id')->constrained(config('affiliate-marketing.tables.affiliates', 'affiliates'))->cascadeOnDelete();
            $table->string('reference', 191)->unique(); $table->decimal('amount', 14, 2); $table->char('currency', 3);
            $table->decimal('commission_rate', 7, 4); $table->decimal('commission_amount', 14, 2);
            $table->string('status', 20)->default('pending'); $table->timestamp('approved_at')->nullable();
            $table->string('rejection_reason')->nullable(); $table->timestamps(); $table->index(['affiliate_id', 'status']);
        });
    }
    public function down(): void {
        Schema::dropIfExists(config('affiliate-marketing.tables.conversions', 'affiliate_conversions'));
        Schema::dropIfExists(config('affiliate-marketing.tables.affiliates', 'affiliates'));
    }
};
