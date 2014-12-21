<?php

namespace N98\Docker\UI;

use N98\Docker\UI\Provider\DockerServiceProvider;
use N98\Docker\UI\Provider\FigServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use DerAlex\Silex\YamlConfigServiceProvider;

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
        $configDist = __DIR__ . '/../config.dist.yaml';
        $configUser = __DIR__ . '/../config.yaml';
        if (file_exists($configUser)) {
            $this->register(new YamlConfigServiceProvider($configUser));
        } else {
            $this->register(new YamlConfigServiceProvider($configDist));
        }

        $this['debug'] = $this['config']['debug'];

        /**
         * Fig
         */
        if ($this['config']['fig']['enabled']) {
            $this->register(new FigServiceProvider());
        }

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

        $this->mount('/container', new Controller\ContainerControllerProvider());
        $this->mount('/image', new Controller\ImageControllerProvider());

        if ($this['config']['fig']['enabled']) {
            $this->mount('/fig', new Controller\FigControllerProvider());
        }

    }
}