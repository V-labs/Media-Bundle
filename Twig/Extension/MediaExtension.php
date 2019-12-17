<?php

namespace Vlabs\MediaBundle\Twig\Extension;

use Symfony\Component\Asset\Packages;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Vlabs\MediaBundle\Manager\MediaManagerInterface;
use Vlabs\MediaBundle\MediaInterface;

class MediaExtension extends AbstractExtension
{
    /**
     * @var MediaManagerInterface
     */
    private $mediaManager;

    /**
     * @var Packages
     */
    private $packages;

    /**
     * MediaExtension constructor.
     *
     * @param MediaManagerInterface $manager
     * @param Packages              $packages
     */
    public function __construct(MediaManagerInterface $manager, Packages $packages)
    {
        $this->mediaManager = $manager;
        $this->packages     = $packages;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('vlabs_media', [$this, 'media'])
        ];
    }

    /**
     * Get Media URI
     *
     * @param MediaInterface $media
     * @param array $options
     *
     * @return string
     */
    public function media(MediaInterface $media, $options = [])
    {
        $absolute = isset($options['absolute']) ? $options['absolute'] : false;
        $thumb    = isset($options['thumb'])    ? $options['thumb']    : false;

        if ($this->mediaManager->hasMedia($media, $thumb)) {
            return $this->mediaManager->getUri($media, $absolute, $thumb);
        }

        return $this->packages->getUrl('/bundles/vlabsmedia/images/loader.svg');
    }
}
