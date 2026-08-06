<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\ExportWeddingPlanExcelAction;
use App\Http\Requests\StoreBudgetItemRequest;
use App\Http\Requests\StoreGuestRequest;
use App\Http\Requests\UpdateWorkspaceSettingsRequest;
use App\Models\Guest;
use App\Models\WeddingMemory;
use App\Models\Wish;
use App\Modules\Budget\Actions\CreateBudgetItemAction;
use App\Modules\Budget\Actions\DeleteBudgetItemAction;
use App\Modules\Budget\Actions\RecordPaymentAction;
use App\Modules\Budget\Models\BudgetItem;
use App\Modules\Budget\Services\CashFlowCalculatorService;
use App\Modules\Guest\Actions\CreateGuestAction;
use App\Modules\Guest\Actions\CreateTableAction;
use App\Modules\Guest\Actions\DeleteGuestAction;
use App\Modules\Guest\Actions\UpdateGuestAction;
use App\Modules\Guest\Models\Table;
use App\Modules\Workspace\Actions\UpdateWorkspaceSettingsAction;
use App\Modules\Workspace\Models\Workspace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class WeddingController extends Controller
{
    public function __construct(
        protected CashFlowCalculatorService $cashFlowCalculator = new CashFlowCalculatorService
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

        $wishes = Wish::where('is_approved', true)
            ->latest()
            ->take(20)
            ->get();

        $memories = WeddingMemory::where('is_approved', true)
            ->latest()
            ->get();

        return Inertia::render('Wedding/Index', [
            'templateSlug' => 'romantic-pastel',
            'wishes' => $wishes,
            'memories' => $memories,
            'guest' => null,
            'workspace' => Workspace::find($workspaceId),
        ]);
    }

    public function invitation(string $templateSlugOrGuestSlug, ?string $guestSlug = null): Response
    {
        $actualGuestSlug = $guestSlug ?? $templateSlugOrGuestSlug;
        $actualTemplateSlug = $guestSlug ? $templateSlugOrGuestSlug : 'romantic-pastel';

        $guest = Guest::where('guest_slug', $actualGuestSlug)->firstOrFail();

        $wishes = Wish::where('is_approved', true)
            ->latest()
            ->take(20)
            ->get();

        $memories = WeddingMemory::where('is_approved', true)
            ->latest()
            ->get();

        return Inertia::render('Wedding/Show', [
            'templateSlug' => $actualTemplateSlug,
            'guest' => $guest,
            'wishes' => $wishes,
            'memories' => $memories,
        ]);
    }

    public function uploadMemory(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'uploader_name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:10240',
            'guest_id' => 'nullable|string|exists:guests,id',
        ]);

        $path = $request->file('image')->store('wedding/memories', 'public');
        $url = Storage::url($path);

        $memory = WeddingMemory::create([
            'guest_id' => $validated['guest_id'] ?? null,
            'uploader_name' => $validated['uploader_name'],
            'title' => $validated['title'] ?? 'Kỷ niệm cùng Dâu Rể',
            'description' => $validated['description'] ?? null,
            'image_url' => $url,
            'category' => 'guest_upload',
            'is_approved' => true,
        ]);

        return response()->json([
            'success' => true,
            'memory' => $memory,
        ]);
    }

    public function budget(Request $request): Response
    {
        $workspaceId = $this->getActiveWorkspaceId($request);
        $workspace = Workspace::find($workspaceId);

        $budgetItems = BudgetItem::forWorkspace($workspaceId)
            ->latest()
            ->get();

        $summary = $this->cashFlowCalculator->calculateOverview($workspaceId);

        return Inertia::render('Wedding/Budget', [
            'workspace' => $workspace,
            'budgetItems' => $budgetItems,
            'summary' => $summary,
        ]);
    }

    public function storeBudgetItem(StoreBudgetItemRequest $request, CreateBudgetItemAction $action): JsonResponse
    {
        $workspaceId = $this->getActiveWorkspaceId($request);
        $item = $action->execute(array_merge($request->validated(), [
            'workspace_id' => $workspaceId,
        ]));

        return response()->json([
            'success' => true,
            'budgetItem' => $item,
            'summary' => $this->cashFlowCalculator->calculateOverview($workspaceId),
        ]);
    }

    public function recordBudgetPayment(Request $request, string $itemId, RecordPaymentAction $action): JsonResponse
    {
        $workspaceId = $this->getActiveWorkspaceId($request);
        $validated = $request->validate(['amount' => 'required|numeric|min:0.01']);

        $item = $action->execute($itemId, (float) $validated['amount']);

        return response()->json([
            'success' => true,
            'budgetItem' => $item,
            'summary' => $this->cashFlowCalculator->calculateOverview($workspaceId),
        ]);
    }

    public function deleteBudgetItem(Request $request, string $itemId, DeleteBudgetItemAction $action): JsonResponse
    {
        $workspaceId = $this->getActiveWorkspaceId($request);
        $success = $action->execute($itemId);

        return response()->json([
            'success' => $success,
            'summary' => $this->cashFlowCalculator->calculateOverview($workspaceId),
        ]);
    }

    public function guests(Request $request): Response
    {
        $workspaceId = $this->getActiveWorkspaceId($request);
        $workspace = Workspace::find($workspaceId);

        $guests = Guest::forWorkspace($workspaceId)
            ->latest()
            ->get();

        $tables = Table::forWorkspace($workspaceId)
            ->get();

        return Inertia::render('Wedding/Guests', [
            'workspace' => $workspace,
            'guests' => $guests,
            'tables' => $tables,
        ]);
    }

    public function storeGuest(StoreGuestRequest $request, CreateGuestAction $action): JsonResponse
    {
        $workspaceId = $this->getActiveWorkspaceId($request);
        $guest = $action->execute(array_merge($request->validated(), [
            'workspace_id' => $workspaceId,
        ]));

        return response()->json([
            'success' => true,
            'guest' => $guest,
        ]);
    }

    public function updateGuest(Request $request, string $guestId, UpdateGuestAction $action): JsonResponse
    {
        $guest = $action->execute($guestId, $request->all());

        return response()->json([
            'success' => true,
            'guest' => $guest,
        ]);
    }

    public function deleteGuest(string $guestId, DeleteGuestAction $action): JsonResponse
    {
        $success = $action->execute($guestId);

        return response()->json([
            'success' => $success,
        ]);
    }

    public function storeTable(Request $request, CreateTableAction $action): JsonResponse
    {
        $workspaceId = $this->getActiveWorkspaceId($request);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'shape' => 'nullable|string|max:50',
            'zone' => 'nullable|string|max:100',
        ]);

        $table = $action->execute(array_merge($validated, [
            'workspace_id' => $workspaceId,
        ]));

        return response()->json([
            'success' => true,
            'table' => $table,
        ]);
    }

    public function invitationEditor(Request $request): Response
    {
        $workspaceId = $this->getActiveWorkspaceId($request);
        $workspace = Workspace::find($workspaceId);

        return Inertia::render('Wedding/InvitationEditor', [
            'workspace' => $workspace,
        ]);
    }

    public function settings(Request $request): Response
    {
        $workspaceId = $this->getActiveWorkspaceId($request);
        $workspace = Workspace::find($workspaceId);

        return Inertia::render('Wedding/Settings', [
            'workspace' => $workspace,
        ]);
    }

    public function updateSettings(UpdateWorkspaceSettingsRequest $request, UpdateWorkspaceSettingsAction $action): JsonResponse
    {
        $workspaceId = $this->getActiveWorkspaceId($request);
        $workspace = $action->execute($workspaceId, $request->validated());

        return response()->json([
            'success' => true,
            'workspace' => $workspace,
        ]);
    }

    public function documents(Request $request): Response
    {
        $workspaceId = $this->getActiveWorkspaceId($request);
        $workspace = Workspace::find($workspaceId);

        return Inertia::render('Wedding/Documents', [
            'workspace' => $workspace,
        ]);
    }

    public function visualizer(Request $request): Response
    {
        $workspaceId = $this->getActiveWorkspaceId($request);
        $workspace = Workspace::find($workspaceId);

        $tables = Table::forWorkspace($workspaceId)->get();

        return Inertia::render('Wedding/Visualizer', [
            'workspace' => $workspace,
            'tables' => $tables,
        ]);
    }

    public function exportExcel(Request $request, ExportWeddingPlanExcelAction $action): BinaryFileResponse
    {
        $workspaceId = $this->getActiveWorkspaceId($request);
        $filePath = $action->execute($workspaceId);

        $filename = 'Eloria_Wedding_Plan_'.date('Y_m_d').'.xlsx';

        return response()->download($filePath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
