<?php

namespace N98\Docker\UI\Controller;

use Silex\ControllerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FigControllerProvider implements ControllerProviderInterface
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
            return $app['twig']->render('fig/list.html.twig', array('figFiles' => $app['fig.folders']));
        })->bind('fig_list');

        /**
         * Start
         */
        $controllers->post('/start', function(Request $request) use ($app) {
            try {
                $folderIndex = $request->get('index');
                $folders = $app['fig.folders'];
                $configFile = $folders[$folderIndex];
                $client = $app['fig.client']($configFile);
                $message = $client->start();
                $status = true;
            } catch (\RuntimeException $e) {
                $message = $e->getMessage();
                $status = false;
            }

            return new JsonResponse(
                array(
                    'success'   => $status,
                    'message'   => $message
                )
            );
        })->bind('fig_start');

        /**
         * Stop
         */
        $controllers->post('/stop', function(Request $request) use ($app) {
            try {
                $folderIndex = $request->get('index');
                $folders = $app['fig.folders'];
                $configFile = $folders[$folderIndex];
                $client = $app['fig.client']($configFile);
                $message = $client->stop();
                $status = true;
            } catch (\RuntimeException $e) {
                $message = $e->getMessage();
                $status = false;
            }

            return new JsonResponse(
                array(
                    'success'   => $status,
                    'message'   => $message
                )
            );
        })->bind('fig_stop');

        /**
         * Info
         */
        $controllers->get('/info', function() use ($app) {
            return new Response('Hallo Welt');
        })->bind('fig_info');

        /**
         * Logs
         */
        $controllers->post('/logs', function(Request $request) use ($app) {
            try {
                $folderIndex = $request->get('index');
                $folders = $app['fig.folders'];
                $configFile = $folders[$folderIndex];
                $client = $app['fig.client']($configFile);
                $message = $client->logs();
                $status = true;
            } catch (\RuntimeException $e) {
                $message = $e->getMessage();
                $status = false;
            }

            return new JsonResponse(
                array(
                    'success'   => $status,
                    'message'   => $message
                )
            );
        })->bind('fig_logs');

        return $controllers;
    }
}