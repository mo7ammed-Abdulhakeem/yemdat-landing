<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipTier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MembershipTierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tiers = MembershipTier::orderBy('sort_order')->get();
        return view('admin.membership-tiers.index', compact('tiers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.membership-tiers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'features_en' => 'nullable|array',
            'features_ar' => 'nullable|array',
            'sort_order' => 'integer',
        ]);

        // Auto-generate slug from English name if not provided
        $validated['slug'] = Str::slug($validated['name_en']);

        // Ensure slug is unique
        if (MembershipTier::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] .= '-' . time();
        }

        $validated['is_active'] = true;

        MembershipTier::create($validated);

        return redirect()->route('admin.membership-tiers.index')->with('success', 'Membership Tier created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MembershipTier $membershipTier)
    {
        return view('admin.membership-tiers.edit', compact('membershipTier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MembershipTier $membershipTier)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'features_en' => 'nullable|array',
            'features_ar' => 'nullable|array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ]);

        $membershipTier->update($validated);

        return redirect()->route('admin.membership-tiers.index')->with('success', 'Membership Tier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MembershipTier $membershipTier)
    {
        $membershipTier->delete();
        return redirect()->route('admin.membership-tiers.index')->with('success', 'Membership Tier deleted successfully.');
    }

    public function toggle(MembershipTier $membershipTier)
    {
        $membershipTier->update(['is_active' => !$membershipTier->is_active]);
        return back()->with('success', 'Status updated.');
    }
}
