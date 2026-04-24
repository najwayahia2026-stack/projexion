<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\AISimilarityService;
use Illuminate\Http\Request;

class AISimilarityController extends Controller
{
    protected AISimilarityService $similarityService;

    public function __construct(AISimilarityService $similarityService)
    {
        $this->similarityService = $similarityService;
    }

    public function checkSimilarity(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
        ]);

        $project = Project::findOrFail($request->project_id);
        $similarityCheck = $this->similarityService->checkSimilarity($project);

        return response()->json([
            'success' => true,
            'similarity_check' => $similarityCheck,
        ]);
    }

    public function getResults(Project $project)
    {
        $results = $this->similarityService->getSimilarityResults($project);

        return response()->json([
            'success' => true,
            'results' => $results,
        ]);
    }
}
