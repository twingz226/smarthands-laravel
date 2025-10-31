<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInfo;
use Illuminate\Http\Request;

class ContactInfoController extends Controller
{
    public function edit()
    {
        $contactInfo = ContactInfo::first();

        if (!$contactInfo) {
            return redirect()->back()->with('error', 'Contact information not found. Please run the ContactInfoSeeder first.');
        }

        return view('admin.contact.edit', compact('contactInfo'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'service_area' => 'required|string',
            'business_hours' => 'required|string',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'google_business_url' => 'nullable|url',
            'about_content' => 'nullable|string',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'services_offered' => 'nullable|string'
        ]);

        $contactInfo = ContactInfo::first();
        if (!$contactInfo) {
            $contactInfo = new ContactInfo();
        }

        $contactInfo->fill($validated);
        $contactInfo->save();

        return redirect()->back()->with('success', 'Contact and About information updated successfully.');
    }
} 