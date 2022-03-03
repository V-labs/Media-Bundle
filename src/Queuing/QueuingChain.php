<?php

namespace Vlabs\MediaBundle\Queuing;

class QueuingChain
{
    private $queuings = [];

    public function addQueuing(QueuingInterface $queuing, $alias)
    {
        $this->queuings[$alias] = $queuing;
    }

    public function getQueuing($alias)
    {
        if (array_key_exists($alias, $this->queuings)) {
            return $this->queuings[$alias];
        }
    }
} 