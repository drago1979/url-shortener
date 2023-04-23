<?php

namespace App\Http\Controllers\Url;

use App\Http\Controllers\Controller;
use App\Http\Requests\Url\UrlStoreRequest;
use App\Models\Url;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class UrlController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('index');
    }

    /**
     * Store a newly created resource in storage & redirect to "details" page.
     * If resource already exists => just redirect.
     *
     */
    public function store(UrlStoreRequest $request): RedirectResponse
    {
        $urlToStore = $request->validated();

        if (null === $url = Url::where('original_url', $urlToStore)->first()) {
            $url = Url::create($urlToStore);
        }

        return redirect(route('url_show', $url->id));
    }

    /**
     * Display the specified resource.
     */
    public function show(Url $url): View
    {
        return view('details', compact('url'));
    }

    /**
     *  Update "total_visits" attribute & redirect to original URL.
     */
    public function redirect($randomValue): RedirectResponse
    {
        $url = Url::where('random_value', $randomValue)->firstOrFail();

        $url->total_visits = ++$url->total_visits;
        $url->save();

        return redirect($url->original_url);
    }
}
