<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Show the settings form (logo upload).
     */
    public function edit()
    {
        $logo = Setting::getValue('company_logo');
        return view('admin.settings.edit', compact('logo'));
    }

    /**
     * Update the company logo.
     */
    public function update(Request $request)
    {
        $request->validate([
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('company_logo')) {
            // Delete old logo if exists
            $oldLogo = Setting::getValue('company_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            // Store new logo
            $file = $request->file('company_logo');
            $filename = 'company_logo_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('company', $filename, 'public');
            Setting::setValue('company_logo', $path);
        }

        return redirect()->back()->with('success', 'Company logo updated successfully.');
    }
} 