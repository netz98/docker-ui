<?php

namespace N98\Docker\UI\Controller;

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

        /**
         * List
         */
        $controllers->get('/', function () use ($app) {
            $manager = $app['docker']->getContainerManager();

            $containers = array();
            foreach ($manager->findAll(array('all' => 1)) as $container) {
                /* @var $container \Docker\Container */
                $manager->inspect($container);

                $containers[] = $container;
            }

            uasort($containers, function(\Docker\Container $container1, \Docker\Container $container2) {
                if ($container1->getName() > $container2->getName()) {
                    return 1;
                }

                if ($container1->getName() < $container2->getName()) {
                    return -1;
                }

                return 0;
            });

            $containerGroups = array(
                'default' => array()
            );
            foreach ($containers as $container) {
                $containerGroupParts = preg_split('/[-_]+/', $container->getName());
                $containerGroup = array_shift($containerGroupParts);
                $group = trim($containerGroup, '/');
                if (!isset($containerGroups[$group])) {
                    $containerGroups[$group] = array();
                }
                $containerGroups[$group][] = $container;
            }
            foreach ($containerGroups as $containerGroupName => $containerGroup) {
                if (count($containerGroup) == 1) {
                    $containerGroups['default'] = array_merge($containerGroup, $containerGroups['default']);
                    unset($containerGroups[$containerGroupName]);
                }
            }

            ksort($containerGroups);

            return $app['twig']->render(
                'container/list.html.twig',
                array('containerGroups' => $containerGroups, 'config' => $app['config'])
            );
        })->bind('container_list');

        /**
         * Toggle
         */
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

        /**
         * Details
         */
        $controllers->get('/details/{containerId}', function($containerId) use ($app) {
            $manager   = $app['docker']->getContainerManager();
            $container = $manager->find($containerId);

            return $app['twig']->render('container/details.html.twig', array('container' => $container));
        })->bind('container_details');

        return $controllers;
    }
}