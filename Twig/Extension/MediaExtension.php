<?php

namespace Vlabs\MediaBundle\Twig\Extension;

use Vlabs\MediaBundle\Manager\MediaManagerInterface;
use Vlabs\MediaBundle\MediaInterface;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;

class MediaExtension extends \Twig_Extension
{
    private $mediaManager;
    private $assetsHelper;

    public function __construct(MediaManagerInterface $manager, AssetsHelper $assetsHelper)
    {
        $this->mediaManager = $manager;
        $this->assetsHelper = $assetsHelper;
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
        return $this->assetsHelper->getUrl('/bundles/vlabsmedia/images/loader.svg');
    }
}
