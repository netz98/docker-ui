<?php

namespace N98\Docker\UI\Provider;

use Silex\ServiceProviderInterface;
use Silex\Application;
use Docker\Http\DockerClient;
use Docker\Docker;

class DockerServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Application $app An Container instance
     */
    public function register(Application $app)
    {
        $app['docker'] = function() {

            $client = new DockerClient(array(), 'unix:///var/run/docker.sock');
            $docker = new Docker($client);

            return $docker;
        };
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