<?php

namespace Vlabs\MediaBundle\Queuing;

use Vlabs\MediaBundle\MediaInterface;

interface QueuingInterface
{
    /**
     * Create job
     *
     * @param MediaInterface $media
     * @param $thumb
     * @return mixed
     */
    public function enqueue(MediaInterface $media, $thumb);
} 