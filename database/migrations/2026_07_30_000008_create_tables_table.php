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
        Schema::create('tables', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->string('table_name');
            $table->integer('capacity')->default(10);
            $table->string('zone_name')->default('Sảnh Chính');
            $table->string('shape')->default('round'); // round, rectangle
            $table->timestamps();
        });

        if (! Schema::hasColumn('guests', 'table_id')) {
            Schema::table('guests', function (Blueprint $table) {
                $table->foreignUlid('table_id')->nullable()->after('table_name')->constrained('tables')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropForeign(['table_id']);
            $table->dropColumn('table_id');
        });

        Schema::dropIfExists('tables');
    }
};
