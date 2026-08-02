<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Modules\Workspace\Models\Workspace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function upgrade(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'plan' => 'required|string|in:free,pro,enterprise',
            'custom_subdomain' => 'nullable|string|max:50|alpha_dash',
        ]);

        $workspace = Workspace::firstOrFail();

        $plan = $validated['plan'];
        $expiresAt = $plan === 'free' ? null : now()->addYear();

        $workspace->update([
            'subscription_plan' => $plan,
            'subscription_expires_at' => $expiresAt,
            'custom_subdomain' => $validated['custom_subdomain'] ?? $workspace->custom_subdomain ?? $workspace->slug,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Chúc mừng! Đám cưới của bạn đã nâng cấp thành công lên Gói '.strtoupper($plan).' Plan!',
            'workspace' => $workspace,
        ]);
    }
}
