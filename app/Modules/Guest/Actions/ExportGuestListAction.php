<?php

declare(strict_types=1);

namespace App\Modules\Guest\Actions;

use App\Models\Guest;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ExportGuestListAction
{
    public function execute(string $workspaceId): StreamedResponse
    {
        $fileName = 'danh-sach-khach-moi-'.date('Y-m-d').'.csv';

        $guests = Guest::where('workspace_id', $workspaceId)
            ->orderBy('name')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($guests) {
            $file = fopen('php://output', 'w');
            // Write UTF-8 BOM for Microsoft Excel compatibility
            fwrite($file, "\xEF\xBB\xBF");

            // CSV Header Row
            fputcsv($file, [
                'Họ và tên',
                'Số điện thoại',
                'Email',
                'Nhóm khách',
                'Trạng thái RSVP',
                'Số khách đi cùng',
                'Bàn tiệc phân công',
                'Ghi chú / Dị ứng thực phẩm',
                'Mã Thiệp',
            ]);

            foreach ($guests as $guest) {
                fputcsv($file, [
                    $guest->name ?? '',
                    $guest->phone ?? '',
                    $guest->email ?? '',
                    $guest->group ?? 'Chưa phân nhóm',
                    $guest->rsvp_status ?? 'Chưa phản hồi',
                    $guest->plus_ones ?? 0,
                    $guest->table_name ?? 'Chưa xếp bàn',
                    $guest->dietary_notes ?? '',
                    $guest->slug ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
