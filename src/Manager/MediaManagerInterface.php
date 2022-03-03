<?php

namespace Vlabs\MediaBundle\Manager;

use Vlabs\MediaBundle\MediaInterface;
use Vlabs\MediaBundle\Queuing\QueuingInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;

interface MediaManagerInterface
{
    /**
     * Fill MediaEntity from objects infos
     *
     * @param MediaInterface $media
     * @param PropertyMapping $mapping
     */
    public function remapMedia(MediaInterface $media, PropertyMapping $mapping);

    /**
     * Create resize process for the wanted queuing system
     *
     * @param MediaInterface $media
     */
    public function publishResize(MediaInterface $media);

    /**
     * Trigger resize process
     *
     * @param MediaInterface $media
     * @param $thumb
     */
    public function doResize(MediaInterface $media, $thumb);

    /**
     * Get the queuing system for the current media
     *
     * @param MediaInterface $media
     * @return QueuingInterface
     */
    public function getQueuing(MediaInterface $media);

    /**
     * Get a media URI from given config
     *
     * @param MediaInterface $media
     * @param bool $absolute
     * @param null $thumb
     * @return string
     */
    public function getUri(MediaInterface $media, $absolute = false, $thumb = null);

    /**
     * Check if a media exists and its thumbnails
     *
     * @param MediaInterface $media
     * @param bool $thumb
     * @return bool
     */
    public function hasMedia(MediaInterface $media, $thumb = false);

    /**
     * Delete a media and its thumbnails
     *
     * @param MediaInterface $media
     * @return mixed
     */
    public function deleteMedia(MediaInterface $media);
}
