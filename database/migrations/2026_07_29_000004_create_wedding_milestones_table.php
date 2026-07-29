<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wedding_milestones', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('timeframe'); // e.g., "15/07 - 31/07/2026"
            $table->string('icon')->default('Sparkles');
            $table->integer('order')->default(0);
            $table->string('status')->default('in_progress'); // completed, in_progress, pending
            $table->text('summary')->nullable();
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable();
            $table->decimal('budget_allocated', 12, 2)->default(0);
            $table->decimal('budget_spent', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('wedding_tasks', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('milestone_id')->constrained('wedding_milestones')->cascadeOnDelete();
            $table->string('title');
            $table->text('notes')->nullable();
            $table->string('vendor_info')->nullable();
            $table->json('attachments')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->date('due_date')->nullable();
            $table->decimal('estimated_cost', 12, 2)->nullable();
            $table->decimal('actual_cost', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wedding_tasks');
        Schema::dropIfExists('wedding_milestones');
    }
};
