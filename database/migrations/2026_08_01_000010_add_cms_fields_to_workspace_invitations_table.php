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
        Schema::table('workspace_invitations', function (Blueprint $table) {
            if (! Schema::hasColumn('workspace_invitations', 'font_family')) {
                $table->string('font_family')->default('Playfair Display')->after('primary_color');
            }
            if (! Schema::hasColumn('workspace_invitations', 'groom_parents')) {
                $table->string('groom_parents')->nullable()->after('font_family');
            }
            if (! Schema::hasColumn('workspace_invitations', 'bride_parents')) {
                $table->string('bride_parents')->nullable()->after('groom_parents');
            }
            if (! Schema::hasColumn('workspace_invitations', 'event_time')) {
                $table->string('event_time')->nullable()->after('bride_parents');
            }
            if (! Schema::hasColumn('workspace_invitations', 'google_maps_url')) {
                $table->text('google_maps_url')->nullable()->after('event_time');
            }
            if (! Schema::hasColumn('workspace_invitations', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('google_maps_url');
            }
            if (! Schema::hasColumn('workspace_invitations', 'bank_account_number')) {
                $table->string('bank_account_number')->nullable()->after('bank_name');
            }
            if (! Schema::hasColumn('workspace_invitations', 'bank_account_holder')) {
                $table->string('bank_account_holder')->nullable()->after('bank_account_number');
            }
            if (! Schema::hasColumn('workspace_invitations', 'enable_gift_box')) {
                $table->boolean('enable_gift_box')->default(true)->after('enable_qr_checkin');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workspace_invitations', function (Blueprint $table) {
            $table->dropColumn([
                'font_family',
                'groom_parents',
                'bride_parents',
                'event_time',
                'google_maps_url',
                'bank_name',
                'bank_account_number',
                'bank_account_holder',
                'enable_gift_box',
            ]);
        });
    }
};
