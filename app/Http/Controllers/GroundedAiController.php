<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Modules\GroundedAI\Actions\QueryGroundedAiAction;
use App\Modules\Workspace\Models\Workspace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroundedAiController extends Controller
{
    public function __construct(
        protected QueryGroundedAiAction $queryGroundedAiAction = new QueryGroundedAiAction
    ) {}

    protected function getActiveWorkspaceId(Request $request): string
    {
        $workspaceId = $request->input('workspace_id')
            ?? session()->get('active_workspace_id');

        if (! $workspaceId) {
            $workspace = Workspace::firstOrCreate(
                ['slug' => 'eloria-default-workspace'],
                ['name' => 'Lễ Cưới Eloria', 'budget_cap' => 200000000.00]
            );
            $workspaceId = $workspace->id;
            session()->put('active_workspace_id', $workspaceId);
        }

        return (string) $workspaceId;
    }

    public function query(Request $request): JsonResponse
    {
        $workspaceId = $this->getActiveWorkspaceId($request);
        $queryInput = (string) ($request->input('query') ?? $request->input('intent') ?? 'overview');
        $chatHistory = (array) ($request->input('history') ?? []);

        $result = $this->queryGroundedAiAction->execute($workspaceId, $queryInput, $chatHistory);

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }
}
