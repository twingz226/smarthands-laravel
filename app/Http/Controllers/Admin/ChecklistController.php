<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checklist;
use App\Http\Requests\Admin\StoreChecklistRequest;
use App\Http\Requests\Admin\UpdateChecklistRequest;

class ChecklistController extends Controller
{
    public function index()
    {
        $checklists = Checklist::latest()->paginate(10);
        return view('admin.checklists.index', compact('checklists'));
    }

    public function create()
    {
        return view('admin.checklists.create');
    }

    public function store(StoreChecklistRequest $request)
    {
        Checklist::create($request->validated());
        return redirect()->route('checklists.index')->with('success', 'Checklist created successfully');
    }

    public function show(Checklist $checklist)
    {
        return view('admin.checklists.show', compact('checklist'));
    }

    public function edit(Checklist $checklist)
    {
        return view('admin.checklists.edit', compact('checklist'));
    }

    public function update(UpdateChecklistRequest $request, Checklist $checklist)
    {
        $checklist->update($request->validated());
        return redirect()->route('checklists.index')->with('success', 'Checklist updated successfully');
    }

    public function destroy(Checklist $checklist)
    {
        $checklist->delete();
        return redirect()->route('checklists.index')->with('success', 'Checklist deleted successfully');
    }
}