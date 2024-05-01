<?php

namespace Tempest\Wcode\Tests;

use Orchestra\Testbench\TestCase;
use Tempest\Wcode\WcodeProvider;

class RoutesTest extends TestCase
{
    public function testRoutesAreRegistered(): void
    {
        $response = $this->get('__tempest/w-code/config');

        $response->assertJsonFragment(['cache-on-client' => true]);

        $response = $this->post('__tempest/w-code/highlight', [
            'language' => 'php',
            'code' => 'print "hello world";'
        ]);

        $response->assertContent('<span class="hl-keyword">print</span> "<span class="hl-value">hello world</span>";');
    }

    protected function getPackageProviders($app): array
    {
        return [
            WcodeProvider::class,
        ];
    }
}
