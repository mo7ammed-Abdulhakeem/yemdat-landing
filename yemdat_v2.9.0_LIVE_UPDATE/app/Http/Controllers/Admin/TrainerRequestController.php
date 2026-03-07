<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainerRequest;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class TrainerRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = TrainerRequest::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%")
                    ->orWhere('help_topic', 'like', "%{$search}%");
            });
        }

        $requests = $query->latest()->paginate(20);

        return view('admin.trainers.index', compact('requests'));
    }

    public function show(TrainerRequest $trainerRequest)
    {
        return view('admin.trainers.show', compact('trainerRequest'));
    }

    public function destroy(TrainerRequest $trainerRequest)
    {
        $trainerRequest->delete();
        return redirect()->route('admin.trainers.index')->with('success', 'Trainer request deleted successfully.');
    }
}
