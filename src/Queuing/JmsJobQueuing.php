<?php

namespace Vlabs\MediaBundle\Queuing;

use Vlabs\MediaBundle\MediaInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JMS\JobQueueBundle\Entity\Job;

class JmsJobQueuing implements QueuingInterface
{
    /**
     * @var ObjectManager
     */
    protected $om;

    public function setObjectManager(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * {@inheritdoc}
     */
    public function enqueue(MediaInterface $media, $thumb)
    {
        $job = new Job('vlabs:media_resize', [
            'mediaId' => $media->getId(),
            'thumb' => $thumb
        ], true, 'media', Job::PRIORITY_HIGH);
        $this->om->persist($job);
        $this->om->flush();
    }
}
