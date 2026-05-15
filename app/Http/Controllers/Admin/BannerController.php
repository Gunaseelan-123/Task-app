<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function index(): View
    {
        $banners = Banner::query()->latest()->paginate(10);

        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'image_url' => ['required', 'url'],
            'cta_label' => ['nullable', 'string', 'max:80'],
            'cta_url' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Banner::query()->create([
            ...$data,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return back()->with('success', 'Banner saved.');
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'image_url' => ['required', 'url'],
            'cta_label' => ['nullable', 'string', 'max:80'],
            'cta_url' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $banner->update([
            ...$data,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return back()->with('success', 'Banner updated.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $banner->delete();

        return back()->with('success', 'Banner removed.');
    }
}
