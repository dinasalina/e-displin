<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Models\AiPromptHistory;
use App\Models\RekodDisiplin;
use App\Services\AiDisciplineService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AiInsightController extends Controller
{
    public function generate(Request $request, AiDisciplineService $aiService): JsonResponse
    {
        $rekod = $request->filled('rekod_disiplin_id')
            ? RekodDisiplin::with(['murid', 'kategoriDisiplin'])->find($request->rekod_disiplin_id)
            : null;

        $insight = $aiService->generateCaseInsight($request->user(), $rekod, $request->context);

        return response()->json([
            'status' => 'success',
            'model' => config('ai.default_model'),
            'data' => $insight,
        ]);
    }

    public function history(): View
    {
        $historyList = AiPromptHistory::with(['pengguna', 'rekodDisiplin', 'sekolah'])
            ->latest()
            ->paginate(15);

        return view('ai.history', compact('historyList'));
    }
}
