<?php

namespace N98\Docker\UI\Fig;

use Symfony\Component\Process\Process;

class Client
{
    /**
     * @var Config\File
     */
    protected $config;

    /**
     * @var string
     */
    protected $figBin;

    /**
     * @param Config\File $config
     */
    public function __construct(Config\File $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getFigBin()
    {
        return $this->figBin;
    }

    /**
     * @param string $figBin
     */
    public function setFigBin($figBin)
    {
        $this->figBin = $figBin;
    }

    /**
     * @return string
     *
     * @throws \RuntimeException
     */
    public function start()
    {
        $process = new Process($this->figBin . ' start');
        $process->setWorkingDirectory($this->config->getPath());
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        return 'ok';
    }

    /**
     * @return string
     *
     * @throws \RuntimeException
     */
    public function stop()
    {
        $process = new Process($this->figBin . ' stop');
        $process->setWorkingDirectory($this->config->getPath());
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        return 'ok';
    }

    /**
     * @return string
     *
     * @throws \RuntimeException
     */
    public function logs()
    {
        $process = new Process($this->figBin . ' logs');
        $process->setWorkingDirectory($this->config->getPath());
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        return $process->getOutput();
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        $process = new Process($this->figBin . ' ps');
        $process->setWorkingDirectory($this->config->getPath());
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        return $process->getOutput();
    }
}