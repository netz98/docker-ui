<?php

namespace N98\Docker\UI\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
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
     * @param Container $pimple An Container instance
     */
    public function register(Container $pimple)
    {
        $pimple['docker'] = function() {

            $client = new DockerClient(array(), 'unix:///var/run/docker.sock');
            $docker = new Docker($client);

            return $docker;
        };
    }
}