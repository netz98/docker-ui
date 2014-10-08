<?php

namespace N98\Docker\UI;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

class ContainerControllerProvider implements ControllerProviderInterface
{

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function () use ($app) {
            $manager = $app['docker']->getContainerManager();

            $containers = array();
            foreach ($manager->findAll(array('all' => 1)) as $container) {
                /* @var $container \Docker\Container */
                $manager->inspect($container);

                $containers[] = $container;
            }

            return $app['twig']->render('container/list.html.twig', array('containers' => $containers));
        })->bind('container_list');

        $controllers->post('/toggle', function(Request $request) use ($app) {
            try {
                $manager   = $app['docker']->getContainerManager();
                $container = $manager->find($request->get('id'));
                $runtimeData = $container->getRuntimeInformations();
                if ($runtimeData['State']['Running']) {
                    $app['docker']->getContainerManager()->stop($container);
                } else {
                    $app['docker']->getContainerManager()->start($container);
                }

                $status = true;
            } catch (Exception $e) {
                $status = false;
                $message = $e->getMessage();
            }

            return new JsonResponse(
                array(
                    'success'   => $status,
                    'container' => $container->getId(),
                    'message'   => $status ? 'OK' : $message
                )
            );
        })->bind('toggle');

        $controllers->get('/details/{containerId}', function($containerId) use ($app) {
            $manager   = $app['docker']->getContainerManager();
            $container = $manager->find($containerId);

            return $app['twig']->render('container/details.html.twig', array('container' => $container));
        })->bind('container_details');

        return $controllers;
    }
}