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
        Schema::create('workspaces', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('groom_name')->nullable()->default('Nguyễn Hoàng Quốc Trung');
            $table->string('bride_name')->nullable()->default('Lê Thị Hồng Vân');
            $table->date('wedding_date')->nullable();
            $table->string('wedding_location')->nullable()->default('TP. Hồ Chí Minh');
            $table->string('venue_name')->nullable()->default('Trung tâm Tiệc cưới Center Palace');
            $table->integer('estimated_guests')->default(200);
            $table->string('wedding_hashtag')->nullable()->default('#TrungVanWedding2026');
            $table->text('couple_story')->nullable();
            $table->decimal('budget_cap', 12, 2)->default(350000000);
            $table->string('ceremony_type')->default('traditional_south'); // traditional_south, traditional_north, catholic_church, destination_outdoor, hotel_luxury
            $table->string('wedding_vibe')->default('pastel'); // pastel, royal_gold, botanical, minimalist
            $table->string('region')->default('hcm'); // hcm, hanoi, danang, western, destination
            $table->string('currency')->default('VND');
            $table->timestamps();
        });

        Schema::create('workspace_members', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('member_name')->nullable();
            $table->string('role')->default('bride'); // owner, bride, groom, planner, family, vendor
            $table->json('permissions')->nullable();
            $table->timestamps();
        });

        Schema::create('invitation_templates', function (Blueprint $table) {
            $table->string('id')->primary(); // romantic-pastel, royal-gold, modern-slate, botanical-sage
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->string('vue_component');
            $table->boolean('is_premium')->default(false);
            $table->timestamps();
        });

        Schema::create('workspace_invitations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('workspace_id')->unique()->constrained('workspaces')->cascadeOnDelete();
            $table->string('template_id')->default('romantic-pastel')->references('id')->on('invitation_templates');
            $table->string('custom_title')->nullable();
            $table->string('primary_color')->default('#EC4899');
            $table->string('music_url')->nullable();
            $table->string('cover_photo_url')->nullable();
            $table->boolean('enable_wax_seal')->default(true);
            $table->boolean('enable_qr_checkin')->default(true);
            $table->timestamps();
        });

        // Add workspace_id foreign keys to existing tables
        if (! Schema::hasColumn('guests', 'workspace_id')) {
            Schema::table('guests', function (Blueprint $table) {
                $table->foreignUlid('workspace_id')->nullable()->after('id')->constrained('workspaces')->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('wishes', 'workspace_id')) {
            Schema::table('wishes', function (Blueprint $table) {
                $table->foreignUlid('workspace_id')->nullable()->after('id')->constrained('workspaces')->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('wedding_milestones', 'workspace_id')) {
            Schema::table('wedding_milestones', function (Blueprint $table) {
                $table->foreignUlid('workspace_id')->nullable()->after('id')->constrained('workspaces')->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('wedding_memories', 'workspace_id')) {
            Schema::table('wedding_memories', function (Blueprint $table) {
                $table->foreignUlid('workspace_id')->nullable()->after('id')->constrained('workspaces')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wedding_memories', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });

        Schema::table('wedding_milestones', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });

        Schema::table('wishes', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });

        Schema::table('guests', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });

        Schema::dropIfExists('workspace_invitations');
        Schema::dropIfExists('invitation_templates');
        Schema::dropIfExists('workspace_members');
        Schema::dropIfExists('workspaces');
    }
};
