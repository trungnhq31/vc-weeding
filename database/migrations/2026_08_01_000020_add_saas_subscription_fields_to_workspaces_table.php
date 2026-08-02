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
        Schema::table('workspaces', function (Blueprint $table) {
            if (! Schema::hasColumn('workspaces', 'subscription_plan')) {
                $table->string('subscription_plan')->default('free')->after('currency');
            }
            if (! Schema::hasColumn('workspaces', 'subscription_expires_at')) {
                $table->timestamp('subscription_expires_at')->nullable()->after('subscription_plan');
            }
            if (! Schema::hasColumn('workspaces', 'custom_subdomain')) {
                $table->string('custom_subdomain')->nullable()->unique()->after('subscription_expires_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workspaces', function (Blueprint $table) {
            $table->dropColumn(['subscription_plan', 'subscription_expires_at', 'custom_subdomain']);
        });
    }
};
