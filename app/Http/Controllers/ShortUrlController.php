<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Url;
use App\Models\UrlClick;
use Illuminate\Http\RedirectResponse;

class ShortUrlController extends Controller
{
    public function redirect(string $shortCode): RedirectResponse
    {
        $url = Url::where('short_code', $shortCode)->firstOrFail();

        UrlClick::create([
            'url_id' => $url->id,
            'clicked_at' => now(),
        ]);

        return redirect()->away($url->original_url);
    }
}
