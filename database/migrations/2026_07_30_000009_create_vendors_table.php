<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('vendors')) {
            Schema::create('vendors', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->foreignUlid('workspace_id')->nullable()->constrained('workspaces')->nullOnDelete();
                $table->string('name');
                $table->string('category')->default('venue'); // venue, studio, makeup, florist, attire, catering, other
                $table->string('vibe_category')->default('pastel'); // pastel, royal, garden, minimalist
                $table->string('city')->nullable()->default('TP. Hồ Chí Minh');
                $table->string('district')->nullable()->default('Quận 1');
                $table->string('address')->nullable();
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->string('price_tier')->default('standard'); // budget, standard, premium, luxury
                $table->decimal('rating', 3, 1)->default(4.9);
                $table->integer('min_capacity')->default(50);
                $table->integer('max_capacity')->default(500);
                $table->json('match_tags')->nullable();
                $table->json('portfolio_images')->nullable();
                $table->string('contact_name')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->decimal('contract_amount', 12, 2)->default(0);
                $table->decimal('paid_amount', 12, 2)->default(0);
                $table->string('payment_status')->default('unpaid'); // unpaid, partially_paid, fully_paid
                $table->date('due_date')->nullable();
                $table->string('contract_file')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
