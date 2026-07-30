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
        Schema::create('tasks', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->string('category')->default('other');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('todo'); // todo, in_progress, done, blocked
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->dateTime('due_date')->nullable();
            $table->decimal('estimated_cost', 12, 2)->default(0);
            $table->decimal('actual_cost', 12, 2)->default(0);
            $table->boolean('is_reminded')->default(false);
            $table->timestamps();
        });

        Schema::create('budget_items', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->string('category_name');
            $table->string('item_name');
            $table->decimal('estimated_amount', 12, 2)->default(0);
            $table->decimal('actual_amount', 12, 2)->default(0);
            $table->decimal('deposit_paid', 12, 2)->default(0);
            $table->string('payment_status')->default('unpaid'); // unpaid, deposit_paid, fully_paid, overdue
            $table->date('due_payment_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_items');
        Schema::dropIfExists('tasks');
    }
};
