<?php

namespace Kaster\Cms\Controllers;

use Illuminate\Contracts\View\View;
use Kaster\Cms\Models\Page;

class PagesController extends Controller
{
    public function show(?string $page = null): View
    {
        if (request()->is('/')) {
            $page = '/';
        }

        $page = Page::query()->where('slug', $page)
            ->where('status', 'published')
            ->with('media')
            ->whereNull('deleted_at')
            ->firstOrFail();

        return view('cms::index', [
            'page' => $page,
        ]);
    }
}
