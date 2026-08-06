<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wedding_run_of_shows', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->string('session_type')->default('ceremony'); // morning_ceremony, evening_reception, party
            $table->string('time_slot'); // e.g. "07:30 - 08:00"
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('person_in_charge')->nullable(); // PIC name
            $table->string('pic_phone')->nullable();
            $table->string('location_note')->nullable(); // e.g. "Sảnh A, Nhà Thờ, Gia Tiên"
            $table->boolean('is_completed')->default(false);
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wedding_run_of_shows');
    }
};
