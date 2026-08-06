<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wedding_gift_logs', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->foreignUlid('guest_id')->nullable()->constrained('guests')->nullOnDelete();
            $table->string('giver_name');
            $table->string('relationship')->default('friend'); // groom_friend, bride_friend, family, colleague
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->string('gift_type')->default('cash'); // cash, transfer, gift_item
            $table->string('gift_item_description')->nullable();
            $table->text('wish_message')->nullable();
            $table->boolean('thank_you_sent')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wedding_gift_logs');
    }
};
