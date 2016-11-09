<?php

namespace Vlabs\MediaBundle\Twig\Extension;

use Symfony\Component\Asset\Packages;
use Vlabs\MediaBundle\Manager\MediaManagerInterface;
use Vlabs\MediaBundle\MediaInterface;

class MediaExtension extends \Twig_Extension
{
    private $mediaManager;
    private $packages;

    public function __construct(MediaManagerInterface $manager, Packages $packages)
    {
        $this->mediaManager = $manager;
        $this->packages = $packages;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'vlabs_media';
    }


    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('vlabs_media', array($this, 'media')),
        );
    }

    /**
     * Get Media URI
     *
     * @param MediaInterface $media
     * @param array $options
     * @return string
     */
    public function media(MediaInterface $media, $options = [])
    {
        $absolute = isset($options['absolute']) ? $options['absolute'] : false;
        $thumb = isset($options['thumb']) ? $options['thumb'] : false;

        if ($this->mediaManager->hasMedia($media, $thumb)) {
            return $this->mediaManager->getUri($media, $absolute, $thumb);
        }
        return $this->packages->getUrl('/bundles/vlabsmedia/images/loader.svg');
    }
}
