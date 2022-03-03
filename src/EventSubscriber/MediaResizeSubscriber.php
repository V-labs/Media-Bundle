<?php

namespace Vlabs\MediaBundle\EventSubscriber;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Vlabs\MediaBundle\Manager\MediaManagerInterface;
use Vlabs\MediaBundle\MediaInterface;
use Doctrine\Common\EventSubscriber;

class MediaResizeSubscriber implements EventSubscriber
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var MediaManagerInterface
     */
    protected $mediaManager;

    public function __construct($config = [], MediaManagerInterface $mediaManager)
    {
        $this->config = $config;
        $this->mediaManager = $mediaManager;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'postPersist',
            'postUpdate'
        ];
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $this->onChange($eventArgs);
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $this->onChange($eventArgs);
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     *
     * @return void
     */
    private function onChange(LifecycleEventArgs $eventArgs)
    {
        if (!$eventArgs->getObject() instanceof MediaInterface) {
            return;
        }

        /** @var MediaInterface $media */
        $media = $eventArgs->getObject();

        if (!isset($this->config['resize'][$media->getKey()])) {
            return;
        }

        if ($media->getFilename() == null || !in_array($media->getMimeType(), ['image/jpg', 'image/jpeg', 'image/png'])) {
            return;
        }

        $queuing = $this->mediaManager->getQueuing($media);

        if (method_exists($queuing, 'setObjectManager')) {
            $queuing->setObjectManager($eventArgs->getObjectManager());
        }

        $this->mediaManager->publishResize($media);
    }
}
