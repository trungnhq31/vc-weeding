<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->string('shuttle_bus')->default('no')->after('dietary_preference');
            $table->string('qr_code_token')->nullable()->unique()->after('shuttle_bus');
            $table->boolean('is_checked_in')->default(false)->after('qr_code_token');
            $table->timestamp('checked_in_at')->nullable()->after('is_checked_in');
            $table->string('table_name')->nullable()->after('checked_in_at');
        });

        Schema::create('wedding_memories', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('guest_id')->nullable()->constrained('guests')->nullOnDelete();
            $table->string('uploader_name');
            $table->string('category')->default('guest_upload')->index(); // pre_wedding, engagement, wedding_day, guest_upload, honeymoon
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('image_url');
            $table->boolean('is_approved')->default(true);
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wedding_memories');

        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn([
                'shuttle_bus',
                'qr_code_token',
                'is_checked_in',
                'checked_in_at',
                'table_name',
            ]);
        });
    }
};
