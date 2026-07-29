<?php

declare(strict_types=1);

namespace App\Enums;

enum MilestoneStatus: string
{
    case Completed = 'completed';
    case InProgress = 'in_progress';
    case Pending = 'pending';

    public function label(): string
    {
        return match ($this) {
            self::Completed => 'Đã Hoàn Thành',
            self::InProgress => 'Đang Thực Hiện',
            self::Pending => 'Chờ Chuẩn Bị',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Completed => 'emerald',
            self::InProgress => 'rose',
            self::Pending => 'slate',
        };
    }
}
