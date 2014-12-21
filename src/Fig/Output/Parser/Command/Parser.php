<?php

namespace N98\Docker\UI\Fig\Output\Parser\Command;

interface Parser
{
    /**
     * @return array
     */
    public function parse();
}