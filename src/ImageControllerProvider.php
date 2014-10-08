<?php

namespace N98\Docker\UI;

use Docker\Manager\ImageManager;
use Silex\ControllerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;

class ImageControllerProvider implements ControllerProviderInterface
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
            $images = array();

            $manager = $app['docker']->getImageManager(); /* @var $manager ImageManager */
            $images = $manager->findAll();

            return $app['twig']->render('image/list.html.twig', array('images' => $images));
        })->bind('image_list');

        return $controllers;
    }
}