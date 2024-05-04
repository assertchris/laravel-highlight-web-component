<?php

namespace AC\LaravelHighlightWebComponent;

use Illuminate\Support\ServiceProvider;

class Provider extends ServiceProvider
{
    const string NS = 'highlight-web-component';

    public function boot(): void
    {
        $this->loadsConfig();
        $this->loadsRoutes();
    }

    private function loadsConfig(): void
    {
        $ns = static::NS;

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
