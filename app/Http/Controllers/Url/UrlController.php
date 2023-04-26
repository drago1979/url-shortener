<?php

namespace App\Http\Controllers\Url;

use App\Http\Controllers\Controller;
use App\Http\Requests\Url\UrlStoreRequest;
use App\Mail\UrlShortCreated;
use App\Models\Url;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

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
        $inputData = $request->validated();

        if (null === $url = Url::where('original_url', $inputData['original_url'])->first()) {
            $url = Url::create($inputData);
        }

        if (isset($inputData['email'])) {
            Mail::to($inputData['email'])->send(new UrlShortCreated($url));
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
        $visitedUrls = explode(',', request()->cookie('visited_urls'));
        $url         = Url::where('random_value', $randomValue)->firstOrFail();

        if (!in_array($url->id, $visitedUrls)) {
            $url->unique_visits = ++$url->unique_visits;

            $visitedUrls[] = $url->id;
        }

        $url->total_visits = ++$url->total_visits;
        $url->save();

        $cookieContent = implode(',', $visitedUrls);

        return redirect($url->original_url)->cookie('visited_urls', $cookieContent, 5);
    }
}
