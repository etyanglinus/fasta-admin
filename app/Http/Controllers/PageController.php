<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\CentralLogics\Helpers;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->where('status', 1)->firstOrFail();

        return view('page', compact('page'));
    }
}
