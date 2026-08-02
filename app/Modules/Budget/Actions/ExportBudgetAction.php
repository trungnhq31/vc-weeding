<?php

declare(strict_types=1);

namespace App\Modules\Budget\Actions;

use App\Modules\Budget\Models\BudgetItem;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ExportBudgetAction
{
    public function execute(string $workspaceId): StreamedResponse
    {
        $fileName = 'bao-cao-ngan-sach-'.date('Y-m-d').'.csv';

        $items = BudgetItem::where('workspace_id', $workspaceId)
            ->orderBy('category')
            ->orderBy('title')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($items) {
            $file = fopen('php://output', 'w');
            // Write UTF-8 BOM for Microsoft Excel compatibility
            fwrite($file, "\xEF\xBB\xBF");

            // CSV Header Row
            fputcsv($file, [
                'Danh mục',
                'Hạng mục chi phí',
                'Ngân sách dự kiến (VNĐ)',
                'Chi phí thực tế (VNĐ)',
                'Chênh lệch (VNĐ)',
                'Trạng thái thanh toán',
                'Nhà cung cấp / Vendor',
                'Ghi chú',
            ]);

            foreach ($items as $item) {
                $estimated = (float) ($item->estimated_amount ?? 0);
                $actual = (float) ($item->actual_amount ?? 0);
                $variance = $estimated - $actual;

                fputcsv($file, [
                    $item->category ?? 'Khác',
                    $item->title ?? '',
                    number_format($estimated, 0, ',', '.'),
                    number_format($actual, 0, ',', '.'),
                    number_format($variance, 0, ',', '.'),
                    $item->status ?? 'Chưa thanh toán',
                    $item->vendor_name ?? '',
                    $item->notes ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
