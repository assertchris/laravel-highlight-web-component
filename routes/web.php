<?php

use AC\LaravelHighlightWebComponent\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Tempest\Highlight\Highlighter;

$ns = Provider::NS;

Route::get("{$ns}/config", fn() => [
    'cache-on-client' => Config::get("{$ns}.cache-on-client"),
]);

Route::post("{$ns}/highlight", function(Request $request) use ($ns) {
    $code = $request->input('code');
    $language = $request->input('language');

    $hash = sha1($language.$code);

    $generate = fn() => html_entity_decode(
        (new Highlighter())->parse($code, $language)
    );

    if (Config::get("{$ns}.cache-on-server")) {
        return Cache::remember(
            "tempest.{$ns}.highlight.{$hash}",
            Config::get("{$ns}.cache-on-server-ttl"),
            $generate,
        );
    }

    return $generate();
});
