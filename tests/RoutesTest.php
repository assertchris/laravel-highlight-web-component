<?php

namespace AC\LaravelHighlightWebComponent\Tests;

use AC\LaravelHighlightWebComponent\Provider;
use Orchestra\Testbench\TestCase;

class RoutesTest extends TestCase
{
    public function testRoutesAreRegistered(): void
    {
        $response = $this->get('highlight-web-component/config');

        $response->assertJsonFragment(['cache-on-client' => true]);

        $response = $this->post('highlight-web-component/highlight', [
            'language' => 'php',
            'code' => 'print "hello world";'
        ]);

        $response->assertContent('<span class="hl-keyword">print</span> "<span class="hl-value">hello world</span>";');
    }

    protected function getPackageProviders($app): array
    {
        return [
            Provider::class,
        ];
    }
}
