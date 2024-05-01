<?php

namespace Tempest\Wcode;

use Illuminate\Support\ServiceProvider;

class WcodeProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadsConfig();
        $this->loadsRoutes();
    }

    private function loadsConfig(): void
    {
        $ns = 'w-code';
        $file = "{$ns}.php";
        $path = $this->join(__DIR__, '..', 'config', $file);

        $this->publishes([
            $path => $this->app->configPath($file),
        ]);

        $this->mergeConfigFrom(
            $path, $ns
        );
    }

    private function loadsRoutes(): void
    {
        $this->loadRoutesFrom($this->join(__DIR__, '..', 'routes', 'web.php'));
    }

    private function join(string ...$bits): string
    {
        return join(DIRECTORY_SEPARATOR, $bits);
    }
}
