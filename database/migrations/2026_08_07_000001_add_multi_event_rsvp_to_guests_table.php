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
            $table->string('rsvp_ceremony')->default('pending')->after('rsvp_status');
            $table->string('rsvp_reception')->default('pending')->after('rsvp_ceremony');
            $table->string('rsvp_afterparty')->default('pending')->after('rsvp_reception');
        });
    }

    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn([
                'rsvp_ceremony',
                'rsvp_reception',
                'rsvp_afterparty',
            ]);
        });
    }
};
