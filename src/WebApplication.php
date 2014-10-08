<?php

namespace N98\Docker\UI;

use N98\Docker\UI\Provider\DockerServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;

class WebApplication extends \Silex\Application
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->init();
    }

    /**
     * Init Silex Application.
     *
     * Registers controller providers and twig engine.
     */
    protected function init()
    {
        $this['debug'] = true;

        /**
         * Docker
         */
        $this->register(new DockerServiceProvider());

        /**
         * URL generator
         */
        $this->register(new UrlGeneratorServiceProvider());

        /**
         * Twig
         */
        $this->register(new TwigServiceProvider(), array(
            'twig.path' => __DIR__ . '/views',
        ));

        /**
         * Controllers
         */
        $this->get('/', function() {
            return $this->redirect($this['url_generator']->generate('container_list'));
        })->bind('home');

        $this->mount('/container', new ContainerControllerProvider());
        $this->mount('/image', new ImageControllerProvider());

    }
}