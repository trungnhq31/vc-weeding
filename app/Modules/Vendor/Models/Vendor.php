<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Models;

use App\Modules\Workspace\Concerns\HasWorkspace;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasUlids, HasWorkspace;

    protected $table = 'vendors';

    protected $fillable = [
        'workspace_id',
        'name',
        'category',
        'vibe_category',
        'city',
        'district',
        'latitude',
        'longitude',
        'price_tier',
        'rating',
        'min_capacity',
        'max_capacity',
        'match_tags',
        'portfolio_images',
        'contact_name',
        'phone',
        'email',
        'contract_amount',
        'paid_amount',
        'payment_status',
        'due_date',
        'contract_file',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'contract_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'rating' => 'float',
            'latitude' => 'float',
            'longitude' => 'float',
            'min_capacity' => 'integer',
            'max_capacity' => 'integer',
            'due_date' => 'date',
            'match_tags' => 'array',
            'portfolio_images' => 'array',
        ];
    }

    public function getUnpaidBalanceAttribute(): float
    {
        return max(0, (float) $this->contract_amount - (float) $this->paid_amount);
    }
}
