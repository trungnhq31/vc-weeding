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

test('visualizer page renders with professional wedding planner components', function () {
    $response = $this->get('/wedding/visualizer');

    $response->assertStatus(200);
});
