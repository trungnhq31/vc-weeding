<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Modules\Vendor\Actions\CreateVendorAction;
use App\Modules\Vendor\Actions\RecordVendorPaymentAction;
use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Services\VendorCrmService;
use App\Modules\Workspace\Models\Workspace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

use App\Modules\Vendor\Services\VendorMatchmakerService;

class VendorController extends Controller
{
    public function __construct(
        protected VendorCrmService $vendorCrmService = new VendorCrmService,
        protected VendorMatchmakerService $matchmakerService = new VendorMatchmakerService
    ) {}

    protected function getActiveWorkspaceId(Request $request): string
    {
        $workspaceId = $request->input('workspace_id')
            ?? session()->get('active_workspace_id');

        if (! $workspaceId) {
            $workspace = Workspace::latest()->first();
            if (! $workspace) {
                $workspace = Workspace::create([
                    'name' => 'Đám Cưới Nguyễn Hoàng Quốc Trung & Lê Thị Hồng Vân',
                    'slug' => 'quoc-trung-hong-van',
                    'groom_name' => 'Nguyễn Hoàng Quốc Trung',
                    'bride_name' => 'Lê Thị Hồng Vân',
                    'wedding_date' => '2026-10-24',
                    'budget_cap' => 350000000.00,
                ]);
            }
            $workspaceId = $workspace->id;
            session()->put('active_workspace_id', $workspaceId);
        }

        return (string) $workspaceId;
    }

    public function index(Request $request): Response
    {
        $workspaceId = $this->getActiveWorkspaceId($request);
        $workspace = Workspace::find($workspaceId);

        $selectedVibe = (string) $request->query('vibe', 'pastel');
        $selectedLocation = (string) $request->query('location', $workspace?->wedding_location ?? 'TP. Hồ Chí Minh');

        $vendors = Vendor::forWorkspace($workspaceId)
            ->latest()
            ->get()
            ->map(function (Vendor $vendor) {
                return [
                    'id' => $vendor->id,
                    'name' => $vendor->name,
                    'category' => $vendor->category,
                    'vibe_category' => $vendor->vibe_category ?? 'pastel',
                    'city' => $vendor->city ?? 'TP. Hồ Chí Minh',
                    'district' => $vendor->district ?? 'Quận 1',
                    'contact_name' => $vendor->contact_name,
                    'phone' => $vendor->phone,
                    'email' => $vendor->email,
                    'contract_amount' => (float) $vendor->contract_amount,
                    'paid_amount' => (float) $vendor->paid_amount,
                    'unpaid_balance' => (float) $vendor->unpaid_balance,
                    'payment_status' => $vendor->payment_status,
                    'due_date' => $vendor->due_date?->format('Y-m-d'),
                    'contract_file' => $vendor->contract_file,
                    'notes' => $vendor->notes,
                ];
            });

        $summary = $this->vendorCrmService->getSummary($workspaceId);
        $recommendations = $this->matchmakerService->getRecommendations($workspace, $selectedVibe, $selectedLocation);

        return Inertia::render('Wedding/Vendors', [
            'workspace' => $workspace ? [
                'id' => $workspace->id,
                'name' => $workspace->name,
                'groom_name' => $workspace->groom_name ?? 'Nguyễn Hoàng Quốc Trung',
                'bride_name' => $workspace->bride_name ?? 'Lê Thị Hồng Vân',
                'wedding_date' => $workspace->wedding_date ? (is_string($workspace->wedding_date) ? $workspace->wedding_date : $workspace->wedding_date->format('Y-m-d')) : '2026-10-24',
                'wedding_location' => $workspace->wedding_location ?? 'TP. Hồ Chí Minh',
                'venue_name' => $workspace->venue_name ?? 'Center Palace',
                'estimated_guests' => $workspace->estimated_guests ?? 200,
                'budget_cap' => (float) $workspace->budget_cap,
            ] : null,
            'vendors' => $vendors,
            'summary' => $summary,
            'recommendations' => $recommendations,
            'selectedVibe' => $selectedVibe,
            'selectedLocation' => $selectedLocation,
        ]);
    }

    public function store(Request $request, CreateVendorAction $action): JsonResponse
    {
        $workspaceId = $this->getActiveWorkspaceId($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'contact_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'contract_amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|date',
            'contract_file' => 'nullable|string|max:500',
            'notes' => 'nullable|string',
        ]);

        $vendor = $action->execute(array_merge($validated, [
            'workspace_id' => $workspaceId,
        ]));

        return response()->json([
            'success' => true,
            'vendor' => $vendor,
            'summary' => $this->vendorCrmService->getSummary($workspaceId),
        ]);
    }

    public function recordPayment(Request $request, string $vendorId, RecordVendorPaymentAction $action): JsonResponse
    {
        $workspaceId = $this->getActiveWorkspaceId($request);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $vendor = $action->execute($vendorId, (float) $validated['amount']);

        return response()->json([
            'success' => true,
            'vendor' => [
                'id' => $vendor->id,
                'name' => $vendor->name,
                'paid_amount' => (float) $vendor->paid_amount,
                'unpaid_balance' => (float) $vendor->unpaid_balance,
                'payment_status' => $vendor->payment_status,
            ],
            'summary' => $this->vendorCrmService->getSummary($workspaceId),
        ]);
    }
}
