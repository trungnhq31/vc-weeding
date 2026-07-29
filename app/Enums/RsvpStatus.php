<?php

declare(strict_types=1);

namespace App\Enums;

enum RsvpStatus: string
{
    case Pending = 'pending';
    case Attending = 'attending';
    case Declined = 'declined';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Đang chờ phản hồi',
            self::Attending => 'Xác nhận tham dự',
            self::Declined => 'Rất tiếc vắng mặt',
        };
    }
}
