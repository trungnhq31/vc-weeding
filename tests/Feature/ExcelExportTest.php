<?php

declare(strict_types=1);

use App\Modules\Workspace\Models\Workspace;

beforeEach(function () {
    $this->workspace = Workspace::create([
        'name' => 'Đám Cưới Quốc Trung & Hồng Vân',
        'slug' => 'quoc-trung-hong-van',
        'groom_name' => 'Nguyễn Hoàng Quốc Trung',
        'bride_name' => 'Lê Thị Hồng Vân',
        'wedding_date' => '2026-10-24',
        'budget_cap' => 350000000.00,
    ]);
});

test('it exports entire wedding plan to excel xlsx stream download', function () {
    $response = $this->get('/wedding/export-excel');

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
});
