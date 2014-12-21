<?php

namespace N98\Docker\UI\Fig\Config;

use Symfony\Component\Yaml\Yaml;

class File extends \SplFileInfo
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        if ($this->config == null) {
            $this->config = Yaml::parse(file_get_contents($this->getPathname()));
        }

        return $this->config;
    }
}