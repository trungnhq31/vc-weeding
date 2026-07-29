<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('guest_slug')->unique()->index();
            $table->string('name');
            $table->string('salutation')->nullable();
            $table->string('group')->nullable();
            $table->integer('estimated_count')->default(1);
            $table->integer('confirmed_count')->default(0);
            $table->string('dietary_preference')->nullable();
            $table->string('rsvp_status')->default('pending')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
