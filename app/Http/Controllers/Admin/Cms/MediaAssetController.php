<?php

namespace App\Http\Controllers\Admin\Cms;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class MediaAssetController extends Controller
{
    public function index(Request $request)
    {
        $query = MediaAsset::query();
        if ($search = $request->get('search')) {
            $query->where('title', 'like', "%{$search}%")->orWhere('type', 'like', "%{$search}%");
        }

        $assets = $query->latest()->paginate(15);

        return view('admin-views.cms.media-assets.index', compact('assets'));
    }

    public function create()
    {
        return view('admin-views.cms.media-assets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'file' => 'required|mimes:pdf,png,jpg,jpeg,svg,webp|max:10240',
            'status' => 'nullable|boolean',
        ]);

        $data = $request->only(['title', 'type']);
        $data['status'] = $request->boolean('status');
        $data['file'] = Helpers::upload('cms/media-kit/', $request->file('file')->getClientOriginalExtension(), $request->file('file'));

        MediaAsset::create($data);

        Toastr::success('Media asset added successfully.');

        return redirect()->route('admin.business-settings.cms.media-assets.index');
    }

    public function edit(MediaAsset $mediaAsset)
    {
        return view('admin-views.cms.media-assets.edit', compact('mediaAsset'));
    }

    public function update(Request $request, MediaAsset $mediaAsset)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'file' => 'nullable|mimes:pdf,png,jpg,jpeg,svg,webp|max:10240',
            'status' => 'nullable|boolean',
        ]);

        $data = $request->only(['title', 'type']);
        $data['status'] = $request->boolean('status');

        if ($request->hasFile('file')) {
            $data['file'] = Helpers::update('cms/media-kit/', $mediaAsset->file, $request->file('file')->getClientOriginalExtension(), $request->file('file'));
        }

        $mediaAsset->update($data);

        Toastr::success('Media asset updated successfully.');

        return redirect()->route('admin.business-settings.cms.media-assets.index');
    }

    public function destroy(MediaAsset $mediaAsset)
    {
        $mediaAsset->delete();
        Toastr::success('Media asset deleted successfully.');

        return back();
    }

    public function status(MediaAsset $mediaAsset, $status)
    {
        $mediaAsset->update(['status' => (bool) $status]);

        return response()->json(['status' => 'success']);
    }
}
