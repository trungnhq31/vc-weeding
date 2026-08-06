<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Guest;
use App\Models\WeddingMilestone;
use App\Modules\Budget\Models\BudgetItem;
use App\Modules\Budget\Services\CashFlowCalculatorService;
use App\Modules\Vendor\Models\Vendor;
use App\Modules\Workspace\Models\Workspace;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportWeddingPlanExcelAction
{
    public function __construct(
        protected CashFlowCalculatorService $cashFlowCalculator = new CashFlowCalculatorService
    ) {}

    public function execute(string $workspaceId): string
    {
        $workspace = Workspace::find($workspaceId) ?? Workspace::latest()->first();
        $budgetOverview = $this->cashFlowCalculator->calculateOverview($workspaceId);

        $spreadsheet = new Spreadsheet;
        $spreadsheet->removeSheetByIndex(0); // Remove default initial sheet

        // -------------------------------------------------------------
        // COLOR PALETTE & STYLING DEFINITIONS
        // -------------------------------------------------------------
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '881337']], // Deep Rosewood
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '9F1239']]],
        ];

        $milestoneHeaderStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => '881337'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFE4E6']], // Soft Rose Pink
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FDA4AF']]],
        ];

        $titleStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => '881337'], 'size' => 16],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
        ];

        $subtitleStyle = [
            'font' => ['italic' => true, 'color' => ['rgb' => '64748B'], 'size' => 10],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
        ];

        $totalRowStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => '0F172A'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']], // Slate Light
            'borders' => [
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '94A3B8']],
                'bottom' => ['borderStyle' => Border::BORDER_DOUBLE, 'color' => ['rgb' => '0F172A']],
            ],
        ];

        $borderThin = [
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
        ];

        $zebraStyle = [
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FAFAFA']],
        ];

        $alertRedStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => '991B1B']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEE2E2']],
        ];

        $alertGreenStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => '065F46']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D1FAE5']],
        ];

        $alertAmberStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => '92400E']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEF3C7']],
        ];

        $vnCurrencyFormat = '#,##0" VNĐ"';

        // =============================================================
        // SHEET 1: TỔNG QUAN WORKSPACE & KPI DASHBOARD
        // =============================================================
        $sheet1 = $spreadsheet->createSheet();
        $sheet1->setTitle('Tổng Quan Workspace');
        $sheet1->setShowGridLines(true);

        $sheet1->mergeCells('A1:E1');
        $sheet1->setCellValue('A1', 'ELORIA WEDDING OS — TỔNG QUAN KẾ HOẠCH ĐÁM CƯỚI');
        $sheet1->getStyle('A1')->applyFromArray($titleStyle);
        $sheet1->getRowDimension(1)->setRowHeight(30);

        $sheet1->mergeCells('A2:E2');
        $sheet1->setCellValue('A2', 'Cập nhật thời gian thực từ Workspace: '.($workspace?->name ?? 'Đám Cưới Quốc Trung & Hồng Vân'));
        $sheet1->getStyle('A2')->applyFromArray($subtitleStyle);

        // 4 Key KPI Summary Cards
        $sheet1->setCellValue('A4', 'Trần Ngân Sách');
        $sheet1->setCellValue('A5', (float) $budgetOverview['budget_cap']);

        $sheet1->setCellValue('B4', 'Tổng Chi Thực Tế');
        $sheet1->setCellValue('B5', (float) $budgetOverview['total_actual']);

        $sheet1->setCellValue('C4', 'Đã Giải Ngân (Cọc)');
        $sheet1->setCellValue('C5', (float) $budgetOverview['total_deposit_paid']);

        $sheet1->setCellValue('D4', 'Còn Phải Thanh Toán');
        $sheet1->setCellValue('D5', (float) $budgetOverview['remaining_balance']);

        $sheet1->getStyle('A4:D4')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '475569'], 'size' => 9],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8FAFC']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]],
        ]);
        $sheet1->getStyle('A5:D5')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '881337'], 'size' => 12],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]],
        ]);
        $sheet1->getStyle('A5:D5')->getNumberFormat()->setFormatCode($vnCurrencyFormat);

        // Detailed Metadata Table
        $sheet1->setCellValue('A7', 'Thông Tin Chi Tiết');
        $sheet1->setCellValue('B7', 'Giá Trị Thiết Lập');
        $sheet1->getStyle('A7:B7')->applyFromArray($headerStyle);
        $sheet1->getRowDimension(7)->setRowHeight(26);

        $overviewData = [
            ['Tên Workspace', $workspace?->name ?? 'Đám Cưới Quốc Trung & Hồng Vân'],
            ['Chú Rể', $workspace?->groom_name ?? 'Nguyễn Hoàng Quốc Trung'],
            ['Cô Dâu', $workspace?->bride_name ?? 'Lê Thị Hồng Vân'],
            ['Ngày Tổ Chức', $workspace?->wedding_date ? (is_string($workspace->wedding_date) ? $workspace->wedding_date : $workspace->wedding_date->format('Y-m-d')) : '2026-10-24'],
            ['Địa Điểm', $workspace?->wedding_location ?? 'TP. Hồ Chí Minh'],
            ['Nhà Hàng Tiệc Cưới', $workspace?->venue_name ?? 'White Palace Event Center'],
            ['Dự Kiến Số Khách', ($workspace?->estimated_guests ?? 200).' Khách'],
            ['Trạng Thái Ngân Sách', $budgetOverview['is_overrun_alert'] ? '⚠️ VỠ NGÂN SÁCH (Vượt '.number_format($budgetOverview['overrun_amount']).' VNĐ)' : '✅ An Toàn Trong Tầm Kiểm Soát'],
        ];

        $r1 = 8;
        foreach ($overviewData as $row) {
            $sheet1->setCellValue("A{$r1}", $row[0]);
            $sheet1->setCellValue("B{$r1}", $row[1]);

            if ($row[0] === 'Trạng Thái Ngân Sách') {
                $sheet1->getStyle("A{$r1}:B{$r1}")->applyFromArray($budgetOverview['is_overrun_alert'] ? $alertRedStyle : $alertGreenStyle);
            } else {
                $sheet1->getStyle("A{$r1}:B{$r1}")->applyFromArray($borderThin);
            }
            $sheet1->getRowDimension($r1)->setRowHeight(22);
            $r1++;
        }

        $sheet1->getColumnDimension('A')->setWidth(28);
        $sheet1->getColumnDimension('B')->setWidth(45);
        $sheet1->getColumnDimension('C')->setWidth(25);
        $sheet1->getColumnDimension('D')->setWidth(25);
        $sheet1->getColumnDimension('E')->setWidth(20);

        // =============================================================
        // SHEET 2: LỘ TRÌNH & TIẾN ĐỘ TASK (HIGHLIGHT GROUPED MILESTONES)
        // =============================================================
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Lộ Trình & Task');
        $sheet2->setShowGridLines(true);

        $sheet2->mergeCells('A1:G1');
        $sheet2->setCellValue('A1', 'KẾ HOẠCH LỘ TRÌNH & DỰ TOÁN CÔNG VIỆC CƯỚI');
        $sheet2->getStyle('A1')->applyFromArray($titleStyle);
        $sheet2->getRowDimension(1)->setRowHeight(30);

        $sheet2->setCellValue('A3', 'Mức Ưu Tiên');
        $sheet2->setCellValue('B3', 'Tên Mục Công Việc');
        $sheet2->setCellValue('C3', 'Nhà Cung Cấp / Đối Tác');
        $sheet2->setCellValue('D3', 'Dự Kiến (VNĐ)');
        $sheet2->setCellValue('E3', 'Thực Tế (VNĐ)');
        $sheet2->setCellValue('F3', 'Trạng Thái');
        $sheet2->setCellValue('G3', 'Ghi Chú');
        $sheet2->getStyle('A3:G3')->applyFromArray($headerStyle);
        $sheet2->getRowDimension(3)->setRowHeight(28);

        $milestones = WeddingMilestone::with(['tasks'])->orderBy('order')->get();
        $r2 = 4;
        $totalEstCost = 0;
        $totalActCost = 0;

        foreach ($milestones as $m) {
            // Milestone Section Header Block
            $sheet2->mergeCells("A{$r2}:G{$r2}");
            $sheet2->setCellValue("A{$r2}", "📌 {$m->timeframe} — {$m->title}");
            $sheet2->getStyle("A{$r2}:G{$r2}")->applyFromArray($milestoneHeaderStyle);
            $sheet2->getRowDimension($r2)->setRowHeight(26);
            $r2++;

            if ($m->tasks->count() > 0) {
                foreach ($m->tasks as $t) {
                    $isZebra = ($r2 % 2 === 0);

                    $sheet2->setCellValue("A{$r2}", match ($t->priority) {
                        'urgent' => '🔥 Khẩn cấp',
                        'high' => '⭐ Cao',
                        'medium' => '🔹 Vừa',
                        default => '⚪ Bình thường'
                    });
                    $sheet2->setCellValue("B{$r2}", $t->title);
                    $sheet2->setCellValue("C{$r2}", $t->vendor_info ?? '-');
                    $sheet2->setCellValue("D{$r2}", (float) $t->estimated_cost);
                    $sheet2->setCellValue("E{$r2}", (float) $t->actual_cost);
                    $sheet2->setCellValue("F{$r2}", $t->is_completed ? '✨ Đã hoàn thành' : '⏳ Đang thực hiện');
                    $sheet2->setCellValue("G{$r2}", $t->notes ?? '');

                    $sheet2->getStyle("A{$r2}:G{$r2}")->applyFromArray($borderThin);
                    if ($isZebra) {
                        $sheet2->getStyle("A{$r2}:G{$r2}")->applyFromArray($zebraStyle);
                    }

                    // Highlight Urgent / Completed
                    if ($t->priority === 'urgent') {
                        $sheet2->getStyle("A{$r2}")->applyFromArray($alertRedStyle);
                    }
                    if ($t->is_completed) {
                        $sheet2->getStyle("F{$r2}")->applyFromArray($alertGreenStyle);
                    }

                    $sheet2->getStyle("D{$r2}:E{$r2}")->getNumberFormat()->setFormatCode($vnCurrencyFormat);
                    $sheet2->getStyle("D{$r2}:E{$r2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                    $totalEstCost += (float) $t->estimated_cost;
                    $totalActCost += (float) $t->actual_cost;
                    $sheet2->getRowDimension($r2)->setRowHeight(22);
                    $r2++;
                }
            } else {
                $sheet2->setCellValue("A{$r2}", '⚪ Bình thường');
                $sheet2->setCellValue("B{$r2}", 'Chưa tạo task chi tiết');
                $sheet2->setCellValue("C{$r2}", '-');
                $sheet2->setCellValue("D{$r2}", (float) $m->budget_allocated);
                $sheet2->setCellValue("E{$r2}", (float) $m->budget_spent);
                $sheet2->setCellValue("F{$r2}", '⏳ Đang chuẩn bị');
                $sheet2->setCellValue("G{$r2}", $m->summary ?? '');

                $sheet2->getStyle("A{$r2}:G{$r2}")->applyFromArray($borderThin);
                $sheet2->getStyle("D{$r2}:E{$r2}")->getNumberFormat()->setFormatCode($vnCurrencyFormat);
                $sheet2->getStyle("D{$r2}:E{$r2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                $totalEstCost += (float) $m->budget_allocated;
                $totalActCost += (float) $m->budget_spent;
                $sheet2->getRowDimension($r2)->setRowHeight(22);
                $r2++;
            }
        }

        // Summary Total Row
        $sheet2->setCellValue("A{$r2}", 'TỔNG CỘNG DỰ TOÁN');
        $sheet2->mergeCells("A{$r2}:C{$r2}");
        $sheet2->setCellValue("D{$r2}", $totalEstCost);
        $sheet2->setCellValue("E{$r2}", $totalActCost);
        $sheet2->setCellValue("F{$r2}", '');
        $sheet2->setCellValue("G{$r2}", '');

        $sheet2->getStyle("A{$r2}:G{$r2}")->applyFromArray($totalRowStyle);
        $sheet2->getStyle("D{$r2}:E{$r2}")->getNumberFormat()->setFormatCode($vnCurrencyFormat);
        $sheet2->getStyle("D{$r2}:E{$r2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet2->getRowDimension($r2)->setRowHeight(26);

        $sheet2->getColumnDimension('A')->setWidth(18);
        $sheet2->getColumnDimension('B')->setWidth(40);
        $sheet2->getColumnDimension('C')->setWidth(28);
        $sheet2->getColumnDimension('D')->setWidth(22);
        $sheet2->getColumnDimension('E')->setWidth(22);
        $sheet2->getColumnDimension('F')->setWidth(20);
        $sheet2->getColumnDimension('G')->setWidth(30);

        // =============================================================
        // SHEET 3: NGÂN SÁCH & DÒNG TIỀN (HIGHLIGHT PAYMENTS & OVERRUNS)
        // =============================================================
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Ngân Sách & Dòng Tiền');
        $sheet3->setShowGridLines(true);

        $sheet3->mergeCells('A1:G1');
        $sheet3->setCellValue('A1', 'BẢNG THEO DÕI NGÂN SÁCH & DÒNG TIỀN THANH TOÁN');
        $sheet3->getStyle('A1')->applyFromArray($titleStyle);
        $sheet3->getRowDimension(1)->setRowHeight(30);

        $sheet3->setCellValue('A3', 'Hạng Mục');
        $sheet3->setCellValue('B3', 'Tên Khoản Chi');
        $sheet3->setCellValue('C3', 'Dự Kiến (VNĐ)');
        $sheet3->setCellValue('D3', 'Thực Tế (VNĐ)');
        $sheet3->setCellValue('E3', 'Đã Thanh Toán (VNĐ)');
        $sheet3->setCellValue('F3', 'Hạn Cọc Kế Tiếp');
        $sheet3->setCellValue('G3', 'Trạng Thái Thanh Toán');
        $sheet3->getStyle('A3:G3')->applyFromArray($headerStyle);
        $sheet3->getRowDimension(3)->setRowHeight(28);

        $budgetItems = BudgetItem::forWorkspace($workspaceId)->get();
        $r3 = 4;

        foreach ($budgetItems as $item) {
            $isZebra = ($r3 % 2 === 0);

            $sheet3->setCellValue("A{$r3}", $item->category_name);
            $sheet3->setCellValue("B{$r3}", $item->item_name);
            $sheet3->setCellValue("C{$r3}", (float) $item->estimated_amount);
            $sheet3->setCellValue("D{$r3}", (float) $item->actual_amount);
            $sheet3->setCellValue("E{$r3}", (float) $item->deposit_paid);
            $sheet3->setCellValue("F{$r3}", $item->due_payment_date?->format('Y-m-d') ?? 'Chưa đặt');
            $sheet3->setCellValue("G{$r3}", match ($item->payment_status) {
                'fully_paid' => '✨ Đã hoàn tất',
                'partially_paid', 'deposit_paid' => '💳 Đã cọc 1 phần',
                default => '⚠️ Chưa cọc'
            });

            $sheet3->getStyle("A{$r3}:G{$r3}")->applyFromArray($borderThin);
            if ($isZebra) {
                $sheet3->getStyle("A{$r3}:G{$r3}")->applyFromArray($zebraStyle);
            }

            // Highlights
            if ($item->payment_status === 'fully_paid') {
                $sheet3->getStyle("G{$r3}")->applyFromArray($alertGreenStyle);
            } elseif ($item->payment_status === 'partially_paid' || $item->payment_status === 'deposit_paid') {
                $sheet3->getStyle("G{$r3}")->applyFromArray($alertAmberStyle);
            } else {
                $sheet3->getStyle("G{$r3}")->applyFromArray($alertRedStyle);
            }

            $sheet3->getStyle("C{$r3}:E{$r3}")->getNumberFormat()->setFormatCode($vnCurrencyFormat);
            $sheet3->getStyle("C{$r3}:E{$r3}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet3->getRowDimension($r3)->setRowHeight(22);
            $r3++;
        }

        // Summary Row Formulas
        $sheet3->setCellValue("A{$r3}", 'TỔNG CỘNG NGÂN SÁCH');
        $sheet3->mergeCells("A{$r3}:B{$r3}");
        $sheet3->setCellValue("C{$r3}", '=SUM(C4:C'.($r3 - 1).')');
        $sheet3->setCellValue("D{$r3}", '=SUM(D4:D'.($r3 - 1).')');
        $sheet3->setCellValue("E{$r3}", '=SUM(E4:E'.($r3 - 1).')');
        $sheet3->setCellValue("F{$r3}", '');
        $sheet3->setCellValue("G{$r3}", '');

        $sheet3->getStyle("A{$r3}:G{$r3}")->applyFromArray($totalRowStyle);
        $sheet3->getStyle("C{$r3}:E{$r3}")->getNumberFormat()->setFormatCode($vnCurrencyFormat);
        $sheet3->getStyle("C{$r3}:E{$r3}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet3->getRowDimension($r3)->setRowHeight(26);

        $sheet3->getColumnDimension('A')->setWidth(25);
        $sheet3->getColumnDimension('B')->setWidth(38);
        $sheet3->getColumnDimension('C')->setWidth(22);
        $sheet3->getColumnDimension('D')->setWidth(22);
        $sheet3->getColumnDimension('E')->setWidth(22);
        $sheet3->getColumnDimension('F')->setWidth(18);
        $sheet3->getColumnDimension('G')->setWidth(24);

        // =============================================================
        // SHEET 4: DANH SÁCH KHÁCH MỜI & SƠ ĐỒ BÀN TIỆC
        // =============================================================
        $sheet4 = $spreadsheet->createSheet();
        $sheet4->setTitle('Khách Mời & Bàn Tiệc');
        $sheet4->setShowGridLines(true);

        $sheet4->mergeCells('A1:G1');
        $sheet4->setCellValue('A1', 'DANH SÁCH KHÁCH MỜI & XÁC NHẬN SƠ ĐỒ BÀN TIỆC');
        $sheet4->getStyle('A1')->applyFromArray($titleStyle);
        $sheet4->getRowDimension(1)->setRowHeight(30);

        $sheet4->setCellValue('A3', 'Họ Và Tên');
        $sheet4->setCellValue('B3', 'Nhóm / Quan Hệ');
        $sheet4->setCellValue('C3', 'Số Điện Thoại');
        $sheet4->setCellValue('D3', 'Vị Trí Bàn Tiệc');
        $sheet4->setCellValue('E3', 'Xác Nhận RSVP');
        $sheet4->setCellValue('F3', 'Ghi Chú Khẩu Vị');
        $sheet4->setCellValue('G3', 'Mã Thiệp Slug');
        $sheet4->getStyle('A3:G3')->applyFromArray($headerStyle);
        $sheet4->getRowDimension(3)->setRowHeight(28);

        $guests = Guest::forWorkspace($workspaceId)->get();
        $r4 = 4;

        foreach ($guests as $g) {
            $isZebra = ($r4 % 2 === 0);

            $sheet4->setCellValue("A{$r4}", $g->name);
            $sheet4->setCellValue("B{$r4}", $g->group ?? 'Bạn Học');
            $sheet4->setCellValue("C{$r4}", $g->phone ?? '-');
            $sheet4->setCellValue("D{$r4}", $g->table_name ?? 'Chưa xếp bàn');
            $sheet4->setCellValue("E{$r4}", match ($g->rsvp_status?->value ?? $g->rsvp_status) {
                'attending', 'confirmed', 'yes' => '✅ Đã tham dự',
                'declined' => '❌ Từ chối',
                default => '⏳ Chờ phản hồi'
            });
            $sheet4->setCellValue("F{$r4}", $g->dietary_preference ?? 'Bình thường');
            $sheet4->setCellValue("G{$r4}", $g->guest_slug);

            $sheet4->getStyle("A{$r4}:G{$r4}")->applyFromArray($borderThin);
            if ($isZebra) {
                $sheet4->getStyle("A{$r4}:G{$r4}")->applyFromArray($zebraStyle);
            }

            // RSVP Highlight
            $rsvpVal = $g->rsvp_status?->value ?? $g->rsvp_status;
            if (in_array($rsvpVal, ['attending', 'confirmed', 'yes'])) {
                $sheet4->getStyle("E{$r4}")->applyFromArray($alertGreenStyle);
            } elseif ($rsvpVal === 'declined') {
                $sheet4->getStyle("E{$r4}")->applyFromArray($alertRedStyle);
            } else {
                $sheet4->getStyle("E{$r4}")->applyFromArray($alertAmberStyle);
            }

            $sheet4->getRowDimension($r4)->setRowHeight(22);
            $r4++;
        }

        $sheet4->getColumnDimension('A')->setWidth(26);
        $sheet4->getColumnDimension('B')->setWidth(20);
        $sheet4->getColumnDimension('C')->setWidth(18);
        $sheet4->getColumnDimension('D')->setWidth(28);
        $sheet4->getColumnDimension('E')->setWidth(20);
        $sheet4->getColumnDimension('F')->setWidth(25);
        $sheet4->getColumnDimension('G')->setWidth(25);

        // =============================================================
        // SHEET 5: ĐỐI TÁC VENDOR CRM & HỢP ĐỒNG (HIGHLIGHT UNPAID BALANCE)
        // =============================================================
        $sheet5 = $spreadsheet->createSheet();
        $sheet5->setTitle('Đối Tác Vendor CRM');
        $sheet5->setShowGridLines(true);

        $sheet5->mergeCells('A1:I1');
        $sheet5->setCellValue('A1', 'DANH SÁCH ĐỐI TÁC VENDOR & TÌNH HÌNH HỢP ĐỒNG');
        $sheet5->getStyle('A1')->applyFromArray($titleStyle);
        $sheet5->getRowDimension(1)->setRowHeight(30);

        $sheet5->setCellValue('A3', 'Tên Đối Tác');
        $sheet5->setCellValue('B3', 'Hạng Mục');
        $sheet5->setCellValue('C3', 'Người Liên Hệ');
        $sheet5->setCellValue('D3', 'Số Điện Thoại');
        $sheet5->setCellValue('E3', 'Email');
        $sheet5->setCellValue('F3', 'Giá Trị Hợp Đồng (VNĐ)');
        $sheet5->setCellValue('G3', 'Đã Thanh Toán (VNĐ)');
        $sheet5->setCellValue('H3', 'Còn Nợ (VNĐ)');
        $sheet5->setCellValue('I3', 'Trạng Thái Thanh Toán');
        $sheet5->getStyle('A3:I3')->applyFromArray($headerStyle);
        $sheet5->getRowDimension(3)->setRowHeight(28);

        $vendors = Vendor::forWorkspace($workspaceId)->get();
        $r5 = 4;

        foreach ($vendors as $v) {
            $isZebra = ($r5 % 2 === 0);

            $sheet5->setCellValue("A{$r5}", $v->name);
            $sheet5->setCellValue("B{$r5}", $v->category);
            $sheet5->setCellValue("C{$r5}", $v->contact_name ?? '-');
            $sheet5->setCellValue("D{$r5}", $v->phone ?? '-');
            $sheet5->setCellValue("E{$r5}", $v->email ?? '-');
            $sheet5->setCellValue("F{$r5}", (float) $v->contract_amount);
            $sheet5->setCellValue("G{$r5}", (float) $v->paid_amount);
            $sheet5->setCellValue("H{$r5}", (float) $v->unpaid_balance);
            $sheet5->setCellValue("I{$r5}", match ($v->payment_status) {
                'fully_paid' => '✨ Hoàn tất 100%',
                'partially_paid' => '💳 Đã cọc 1 phần',
                default => '⚠️ Chưa cọc'
            });

            $sheet5->getStyle("A{$r5}:I{$r5}")->applyFromArray($borderThin);
            if ($isZebra) {
                $sheet5->getStyle("A{$r5}:I{$r5}")->applyFromArray($zebraStyle);
            }

            // Highlights
            if ($v->payment_status === 'fully_paid') {
                $sheet5->getStyle("I{$r5}")->applyFromArray($alertGreenStyle);
            } else {
                $sheet5->getStyle("I{$r5}")->applyFromArray($alertAmberStyle);
                $sheet5->getStyle("H{$r5}")->applyFromArray($alertRedStyle); // Highlight remaining debt
            }

            $sheet5->getStyle("F{$r5}:H{$r5}")->getNumberFormat()->setFormatCode($vnCurrencyFormat);
            $sheet5->getStyle("F{$r5}:H{$r5}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet5->getRowDimension($r5)->setRowHeight(22);
            $r5++;
        }

        // Summary Row Formulas
        $sheet5->setCellValue("A{$r5}", 'TỔNG CỘNG HỢP ĐỒNG');
        $sheet5->mergeCells("A{$r5}:E{$r5}");
        $sheet5->setCellValue("F{$r5}", '=SUM(F4:F'.($r5 - 1).')');
        $sheet5->setCellValue("G{$r5}", '=SUM(G4:G'.($r5 - 1).')');
        $sheet5->setCellValue("H{$r5}", '=SUM(H4:H'.($r5 - 1).')');
        $sheet5->setCellValue("I{$r5}", '');

        $sheet5->getStyle("A{$r5}:I{$r5}")->applyFromArray($totalRowStyle);
        $sheet5->getStyle("F{$r5}:H{$r5}")->getNumberFormat()->setFormatCode($vnCurrencyFormat);
        $sheet5->getStyle("F{$r5}:H{$r5}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet5->getRowDimension($r5)->setRowHeight(26);

        $sheet5->getColumnDimension('A')->setWidth(28);
        $sheet5->getColumnDimension('B')->setWidth(20);
        $sheet5->getColumnDimension('C')->setWidth(22);
        $sheet5->getColumnDimension('D')->setWidth(18);
        $sheet5->getColumnDimension('E')->setWidth(26);
        $sheet5->getColumnDimension('F')->setWidth(24);
        $sheet5->getColumnDimension('G')->setWidth(24);
        $sheet5->getColumnDimension('H')->setWidth(22);
        $sheet5->getColumnDimension('I')->setWidth(24);

        // Set active sheet to Sheet 1
        $spreadsheet->setActiveSheetIndex(0);

        // Save file to temporary path
        $tempPath = storage_path('app/temp_wedding_plan_'.time().'.xlsx');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempPath);

        return $tempPath;
    }
}
