<?php

namespace Vlabs\MediaBundle\Queuing;

use Vlabs\MediaBundle\Manager\MediaManagerInterface;
use Vlabs\MediaBundle\MediaInterface;

class SyncQueuing implements QueuingInterface
{
    /**
     * @var MediaManagerInterface
     */
    protected $mediaManager;

    public function setMediaManager(MediaManagerInterface $mediaManager)
    {
        $this->mediaManager = $mediaManager;
    }

    /**
     * @inheritdoc
     */
    public function enqueue(MediaInterface $media, $thumb)
    {
        $this->mediaManager->doResize($media, $thumb);
    }
} 