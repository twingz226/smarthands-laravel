<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeMedia;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $heroMedia = HomeMedia::section('hero')->active()->orderBy('display_order')->get();
        $servicesMedia = HomeMedia::section('services')->active()->orderBy('display_order')->get();

        // Determine active services already published on homepage services section
        $publishedServiceIds = HomeMedia::query()
            ->where('section', 'services')
            ->where('is_active', true)
            ->whereNotNull('service_id')
            ->pluck('service_id')
            ->filter()
            ->unique()
            ->values();

        // Filtered Service Catalog options for Add modal (unpublished services only)
        $serviceOptions = Service::query()
            ->when(method_exists(Service::class, 'scopeActive'), function ($q) {
                return $q->active();
            })
            ->when($publishedServiceIds->isNotEmpty(), function ($q) use ($publishedServiceIds) {
                return $q->whereNotIn('id', $publishedServiceIds);
            })
            ->orderBy('name')
            ->get(['id','name','description','pricing_type','price']);
        
        return view('admin.media.index', compact('heroMedia', 'servicesMedia', 'serviceOptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'section' => 'required|in:hero,services',
            'media_type' => 'required|in:image,video',
            'media_file' => 'required|file|mimes:jpg,jpeg,png,webp,mp4,webm,ogg|max:10240',
            'display_order' => 'required|integer|min:1',
            'price' => 'nullable|string|max:100',
            'service_type' => 'nullable|string|in:hourly,sqm,fixed',
            'service_id' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        try {
            // If adding to Services, ensure a service is selected and not already published as active
            if ($request->section === 'services') {
                if (!$request->filled('service_id')) {
                    return redirect()->back()
                        ->with('error', 'Please select a Service from the Service Catalog.')
                        ->withInput();
                }

                $alreadyPublished = HomeMedia::query()
                    ->where('section', 'services')
                    ->where('is_active', true)
                    ->where('service_id', $request->service_id)
                    ->exists();

                if ($alreadyPublished) {
                    return redirect()->back()
                        ->with('error', 'This service is already published on the homepage.')
                        ->withInput();
                }
            }
            // Handle file upload
            $file = $request->file('media_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->move(public_path('images'), $filename);
            $mediaPath = 'images/' . $filename;

            // Create media record
            HomeMedia::create([
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'service_type' => $request->service_type,
                'service_id' => $request->service_id,
                'media_type' => $request->media_type,
                'media_path' => $mediaPath,
                'section' => $request->section,
                'display_order' => $request->display_order,
                'is_active' => $request->has('is_active')
            ]);

            // Redirect back to originating page if provided
            $returnTo = $request->input('return_to');
            if ($returnTo && filter_var($returnTo, FILTER_VALIDATE_URL)) {
                return redirect()->to($returnTo)
                    ->with('success', 'All set! Your media has been uploaded successfully.');
            }

            return redirect()->route('admin.media.index')
                ->with('success', 'All set! Your media has been uploaded successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to upload media: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HomeMedia $media)
    {
        return response()->json($media);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HomeMedia $media)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'section' => 'required|in:hero,services',
            'media_type' => 'required|in:image,video',
            'media_file' => 'nullable|file|mimes:jpg,jpeg,png,webp,mp4,webm,ogg|max:10240',
            'display_order' => 'required|integer|min:1',
            'price' => 'nullable|string|max:100',
            'service_type' => 'nullable|string|in:hourly,sqm,fixed',
            'service_id' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        try {
            // If switching/editing to Services, ensure no duplicate active service is created
            if ($request->section === 'services' && $request->filled('service_id')) {
                $alreadyPublished = HomeMedia::query()
                    ->where('section', 'services')
                    ->where('is_active', true)
                    ->where('service_id', $request->service_id)
                    ->where('id', '!=', $media->id)
                    ->exists();

                if ($alreadyPublished) {
                    return redirect()->back()
                        ->with('error', 'This service is already published on the homepage.')
                        ->withInput();
                }
            }
            $updateData = [
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'service_type' => $request->service_type,
                'service_id' => $request->service_id,
                'media_type' => $request->media_type,
                'section' => $request->section,
                'display_order' => $request->display_order,
                'is_active' => $request->has('is_active')
            ];

            // Handle file upload if new file provided
            if ($request->hasFile('media_file')) {
                // Delete old file if it exists
                if ($media->media_path && file_exists(public_path($media->media_path))) {
                    unlink(public_path($media->media_path));
                }

                $file = $request->file('media_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images'), $filename);
                $updateData['media_path'] = 'images/' . $filename;
            }

            $media->update($updateData);

            return redirect()->route('admin.media.index')
                ->with('success', 'All set! Your media has been updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update media: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HomeMedia $media)
    {
        try {
            // Delete the file if it exists
            if ($media->media_path && file_exists(public_path($media->media_path))) {
                unlink(public_path($media->media_path));
            }

            $media->delete();

            return response()->json([
                'success' => true,
                'message' => 'All set! Your media has been deleted successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete media: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update display order for media items
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer|exists:home_media,id',
            'items.*.display_order' => 'required|integer|min:1'
        ]);

        try {
            foreach ($request->items as $item) {
                HomeMedia::where('id', $item['id'])
                    ->update(['display_order' => $item['display_order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'All set! Order has been updated successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update display order: ' . $e->getMessage()
            ], 500);
        }
    }
}
