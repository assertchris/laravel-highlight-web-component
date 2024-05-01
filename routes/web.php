<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Tempest\Highlight\Highlighter;

Route::get('__tempest/w-code/config', fn() => [
    'cache-on-client' => Config::get('w-code.cache-on-client'),
]);

Route::post('__tempest/w-code/highlight', function(Request $request) {
    $code = $request->input('code');
    $language = $request->input('language');

    $hash = sha1($language.$code);

    $generate = fn() => html_entity_decode(
        (new Highlighter())->parse($code, $language)
    );

    if (Config::get('w-code.cache-on-server')) {
        return Cache::remember(
            "tempest.w-code.highlight.{$hash}",
            Config::get('w-code.cache-on-server-ttl'),
            $generate,
        );
    }

    return $generate();
});
