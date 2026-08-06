<?php

declare(strict_types=1);

use App\Models\WeddingMemory;
use App\Modules\Workspace\Models\Workspace;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
});

test('workspace admin can view gallery page', function () {
    $workspace = Workspace::create([
        'name' => 'Đám Cưới Test Gallery',
        'slug' => 'test-gallery-slug-1',
        'groom_name' => 'Quốc Trung',
        'bride_name' => 'Hồng Vân',
    ]);

    session()->put('active_workspace_id', $workspace->id);

    $response = $this->get('/wedding/gallery');

    $response->assertStatus(200);
});

test('workspace admin can upload photos to gallery album', function () {
    $workspace = Workspace::create([
        'name' => 'Đám Cưới Test Gallery 2',
        'slug' => 'test-gallery-slug-2',
        'groom_name' => 'Quốc Trung',
        'bride_name' => 'Hồng Vân',
    ]);
    session()->put('active_workspace_id', $workspace->id);

    $file = UploadedFile::fake()->image('pre_wedding_sample.jpg');

    $response = $this->postJson('/wedding/gallery', [
        'category' => 'pre_wedding',
        'title' => 'Ảnh Ngoại Cảnh',
        'description' => 'Bộ ảnh chụp tại Đà Lạt',
        'images' => [$file],
    ]);

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);

    $this->assertDatabaseHas('wedding_memories', [
        'workspace_id' => $workspace->id,
        'category' => 'pre_wedding',
        'title' => 'Ảnh Ngoại Cảnh',
    ]);
});

test('workspace admin can toggle pin and moderation status of photo', function () {
    $workspace = Workspace::create([
        'name' => 'Đám Cưới Test Gallery 3',
        'slug' => 'test-gallery-slug-3',
        'groom_name' => 'Quốc Trung',
        'bride_name' => 'Hồng Vân',
    ]);

    $memory = WeddingMemory::create([
        'workspace_id' => $workspace->id,
        'uploader_name' => 'Chú Rể',
        'category' => 'wedding_day',
        'title' => 'Lễ Gia Tiên',
        'image_url' => '/storage/wedding/sample.jpg',
        'is_approved' => true,
        'is_pinned' => false,
    ]);

    // Pin
    $pinRes = $this->postJson("/wedding/gallery/{$memory->id}/pin");
    $pinRes->assertStatus(200);
    $pinRes->assertJson(['success' => true, 'is_pinned' => true]);

    // Approve / Moderate
    $approveRes = $this->postJson("/wedding/gallery/{$memory->id}/approve");
    $approveRes->assertStatus(200);
    $approveRes->assertJson(['success' => true, 'is_approved' => false]);
});

test('guests can view public gallery and upload live photos', function () {
    $workspace = Workspace::create([
        'name' => 'Đám Cưới Minh Trung',
        'slug' => 'dam-cuoi-minh-trung',
        'groom_name' => 'Minh Trung',
        'bride_name' => 'Hồng Vân',
    ]);

    $response = $this->get('/wedding/shared-gallery/dam-cuoi-minh-trung');
    $response->assertStatus(200);

    $file = UploadedFile::fake()->image('guest_photo.jpg');

    $uploadRes = $this->postJson('/wedding/shared-gallery/dam-cuoi-minh-trung/upload', [
        'uploader_name' => 'Anh Tuấn',
        'description' => 'Chúc hai bạn trăm năm hạnh phúc!',
        'image' => $file,
    ]);

    $uploadRes->assertStatus(200);
    $uploadRes->assertJson(['success' => true]);

    $this->assertDatabaseHas('wedding_memories', [
        'workspace_id' => $workspace->id,
        'uploader_name' => 'Anh Tuấn',
        'category' => 'guest_upload',
    ]);
});
