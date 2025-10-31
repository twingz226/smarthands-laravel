<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeMedia;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class HeroMediaController extends Controller
{
    public function index()
    {
        try {
            $items = HomeMedia::query()
                ->section(HomeMedia::SECTION_HERO)
                ->orderBy('display_order')
                ->orderByDesc('id')
                ->get();
            $servicesMedia = HomeMedia::query()
                ->section(HomeMedia::SECTION_SERVICES)
                ->orderBy('display_order')
                ->orderByDesc('id')
                ->get();
            // Determine services already published on the homepage services section
            $publishedServiceIds = HomeMedia::query()
                ->section(HomeMedia::SECTION_SERVICES)
                ->where('is_active', true)
                ->whereNotNull('service_id')
                ->pluck('service_id')
                ->filter()
                ->unique()
                ->values();

            // Services available to add (not yet published)
            $serviceOptions = Service::query()
                ->when(method_exists(Service::class, 'scopeActive'), function ($q) {
                    return $q->active();
                })
                ->when($publishedServiceIds->isNotEmpty(), function ($q) use ($publishedServiceIds) {
                    return $q->whereNotIn('id', $publishedServiceIds);
                })
                ->orderBy('name')
                ->get(['id','name','description','pricing_type','price']);

            // Full active services list for Edit modal to avoid hiding the current selection
            $allServiceOptions = Service::query()
                ->when(method_exists(Service::class, 'scopeActive'), function ($q) {
                    return $q->active();
                })
                ->orderBy('name')
                ->get(['id','name','description','pricing_type','price']);

            return view('admin.hero_media.index', compact('items', 'servicesMedia', 'serviceOptions', 'allServiceOptions'));
        } catch (\Throwable $e) {
            // Likely the home_media table hasn't been fully migrated yet (missing 'section' column)
            $items = collect();
            $servicesMedia = collect();
            $serviceOptions = collect();
            $allServiceOptions = collect();
            return view('admin.hero_media.index', compact('items', 'servicesMedia', 'serviceOptions', 'allServiceOptions'))
                ->with('error', 'Homepage banner schema is not up to date. Please run migrations.');
        }
    }

    public function create()
    {
        return view('admin.hero_media.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['nullable','string','max:255'],
            'description' => ['nullable','string'],
            'media_type' => ['required','in:image,video'],
            'media_path' => ['required','string','max:1024'], // URL or public path
            'poster_image' => ['nullable','string','max:1024'],
            'display_order' => ['nullable','integer','min:0'],
            'is_active' => ['nullable','boolean'],
        ]);

        $data['section'] = HomeMedia::SECTION_HERO;
        $data['is_active'] = (bool)($data['is_active'] ?? false);
        $data['display_order'] = $data['display_order'] ?? 0;

        HomeMedia::create($data);

        return Redirect::route('admin.hero_media.index')->with('success', 'All set! Your banner has been created successfully.');
    }

    public function edit(HomeMedia $hero_medium)
    {
        // Only allow editing hero section items
        abort_unless($hero_medium->section === HomeMedia::SECTION_HERO, 404);
        return view('admin.hero_media.edit', ['item' => $hero_medium]);
    }

    public function update(Request $request, HomeMedia $hero_medium)
    {
        abort_unless($hero_medium->section === HomeMedia::SECTION_HERO, 404);

        $data = $request->validate([
            'title' => ['nullable','string','max:255'],
            'description' => ['nullable','string'],
            'media_type' => ['required','in:image,video'],
            'media_path' => ['required','string','max:1024'],
            'poster_image' => ['nullable','string','max:1024'],
            'display_order' => ['nullable','integer','min:0'],
            'is_active' => ['nullable','boolean'],
        ]);

        $data['is_active'] = (bool)($data['is_active'] ?? false);
        $data['display_order'] = $data['display_order'] ?? 0;

        $hero_medium->update($data);

        return Redirect::route('admin.hero_media.index')->with('success', 'All set! Your banner has been updated successfully.');
    }

    public function destroy(HomeMedia $hero_medium)
    {
        abort_unless($hero_medium->section === HomeMedia::SECTION_HERO, 404);
        $hero_medium->delete();
        return Redirect::route('admin.hero_media.index')->with('success', 'All set! Your banner has been deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $data = $request->validate([
            'orders' => ['required','array'],
            'orders.*.id' => ['required','integer','exists:home_media,id'],
            'orders.*.display_order' => ['required','integer','min:0'],
        ]);

        foreach ($data['orders'] as $row) {
            $item = HomeMedia::find($row['id']);
            if ($item && $item->section === HomeMedia::SECTION_HERO) {
                $item->update(['display_order' => $row['display_order']]);
            }
        }

        return Redirect::route('admin.hero_media.index')->with('success', 'All set! Order has been updated successfully.');
    }

    /**
     * Upload a new video for the Homepage Banner and set it active.
     */
    public function upload(Request $request)
    {
        $data = $request->validate([
            'media_type' => ['required','in:image,video'],
            'media' => ['required','file','max:204800'], // up to 200MB
            'title' => ['nullable','string','max:255'],
            'is_active' => ['nullable','boolean'],
        ]);

        // Additional mimetype validation based on selected type
        $file = $request->file('media');
        if ($data['media_type'] === HomeMedia::TYPE_VIDEO) {
            $request->validate(['media' => ['mimetypes:video/mp4,video/webm,video/ogg']]);
        } else {
            $request->validate(['media' => ['mimetypes:image/jpeg,image/png,image/webp']]);
        }

        // Store in public disk so it's web-accessible via storage symlink
        $path = $file->store('home_banners', 'public');

        // Deactivate existing hero banners if requested active (default true)
        $setActive = (bool)($data['is_active'] ?? true);
        if ($setActive) {
            HomeMedia::query()->where('section', HomeMedia::SECTION_HERO)->update(['is_active' => false]);
        }

        // Keep it first
        $displayOrder = 0;

        HomeMedia::create([
            'title' => $data['title'] ?? null,
            'description' => null,
            'media_type' => $data['media_type'],
            'media_path' => $path, // Model accessor should resolve to URL
            'poster_image' => null,
            'section' => HomeMedia::SECTION_HERO,
            'display_order' => $displayOrder,
            'is_active' => $setActive,
        ]);

        return Redirect::route('admin.hero_media.index')->with('success', 'All set! Your hero banner has been uploaded successfully.');
    }
}
