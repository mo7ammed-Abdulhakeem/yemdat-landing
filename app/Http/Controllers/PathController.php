<?php

namespace App\Http\Controllers;

use App\Models\LearningPath;

class PathController extends Controller
{
    public function index()
    {
        $paths = LearningPath::published()
            ->ordered()
            ->withCount('steps')
            ->get();

        return view('paths.index', compact('paths'));
    }

    public function show($slug)
    {
        $path = LearningPath::published()
            ->where('slug', $slug)
            ->with(['steps.event'])
            ->firstOrFail();

        // Group steps by their (localized) phase heading, preserving order.
        $groupedSteps = $path->steps->groupBy(fn ($step) => $step->phase ?? '');

        return view('paths.show', compact('path', 'groupedSteps'));
    }
}
