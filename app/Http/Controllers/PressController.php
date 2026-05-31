<?php

namespace App\Http\Controllers;

use App\Models\MediaAsset;
use App\Models\PressRelease;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

class PressController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable('press_releases') || !Schema::hasTable('media_assets')) {
            $pressReleases = new LengthAwarePaginator([], 0, 10);
            $assets = collect();

            return view('press.index', compact('pressReleases', 'assets'));
        }

        $pressReleases = PressRelease::where('status', 1)
            ->latest('publish_date')
            ->paginate(10);

        $assets = MediaAsset::where('status', 1)->orderBy('title')->get();

        return view('press.index', compact('pressReleases', 'assets'));
    }

    public function show($slug)
    {
        abort_if(!Schema::hasTable('press_releases'), 404);

        $pressRelease = PressRelease::where('slug', $slug)->where('status', 1)->firstOrFail();

        return view('press.show', compact('pressRelease'));
    }
}
