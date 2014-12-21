<?php

namespace N98\Docker\UI\Provider;

use N98\Docker\UI\Fig\Client;
use Silex\ServiceProviderInterface;
use Silex\Application;
use N98\Docker\UI\Fig\Config\File as ConfigFile;
use Symfony\Component\Finder\Finder;

class FigServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Application $app An Application instance
     */
    public function register(Application $app)
    {
        $app['fig.folders'] = $app->share(function($app) {
            $folders = $app['config']['fig']['folders'];
            $names = array_flip($folders);
            $finder  = Finder::create()
                ->files()
                ->name('fig.yml')
                ->in($folders);

            $figFiles = array();
            foreach ($finder as $file) {
                /* @var $file Symfony\Component\Finder\SplFileInfo */
                $figConfig = new ConfigFile($file->getPathname());
                $figConfig->setName($names[$file->getPath()]);
                $figFiles[array_search($file->getPathname(), $folders)] = $figConfig;
            }
            return $figFiles;
        });

        $app['fig.bin'] = $app['config']['fig']['bin'];
        $binPath = $app['fig.bin'];

        $app['fig.client'] = $app->protect(function($file) use ($binPath) {
            $client = new Client($file);
            $client->setFigBin($binPath);

            return $client;
        });
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {
        // TODO: Implement boot() method.
    }
}