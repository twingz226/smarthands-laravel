<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DisabledDate;

class DisabledDateController extends Controller
{
    public function index()
    {
        $dates = DisabledDate::orderBy('date', 'asc')->get();
        return view('admin.disabled_dates.index', compact('dates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'reason' => 'nullable|string|max:255',
        ]);

        DisabledDate::updateOrCreate(
            ['date' => $validated['date']],
            [
                'reason' => $validated['reason'] ?? null,
                'is_active' => true,
            ]
        );

        return redirect()->back()->with('success', 'Disabled date added.');
    }

    public function update(Request $request, DisabledDate $disabledDate)
    {
        $validated = $request->validate([
            'reason' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $disabledDate->update($validated);

        return redirect()->back()->with('success', 'Disabled date updated.');
    }

    public function destroy(DisabledDate $disabledDate)
    {
        $disabledDate->delete();
        return redirect()->back()->with('success', 'Disabled date removed.');
    }
}
