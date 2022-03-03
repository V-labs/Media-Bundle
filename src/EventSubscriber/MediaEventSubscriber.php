<?php

namespace Vlabs\MediaBundle\EventSubscriber;

use Vlabs\MediaBundle\Manager\MediaManagerInterface;
use Vlabs\MediaBundle\MediaInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Event\Events;

class MediaEventSubscriber implements EventSubscriberInterface
{
    protected $mediaManager;

    /**
     * @param MediaManagerInterface $mediaManager
     */
    public function __construct(MediaManagerInterface $mediaManager)
    {
        $this->mediaManager = $mediaManager;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents(): array
    {
        return [
            Events::PRE_UPLOAD => 'preUpload',
            Events::PRE_REMOVE => 'preRemove',
        ];
    }

    /**
     * Handle Media Transformation
     *
     * @param Event $event
     */
    public function preUpload(Event $event) :void
    {
        if($event->getObject() instanceof MediaInterface) {
            $this->mediaManager->remapMedia($event->getObject(), $event->getMapping());
        }
    }

    /**
     * Handle Media Deletion
     *
     * @param Event $event
     */
    public function preRemove(Event $event) :void
    {
        if($event->getObject() instanceof MediaInterface) {
            $this->mediaManager->deleteMedia($event->getObject());
        }
    }
}
