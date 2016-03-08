<?php

namespace Vlabs\MediaBundle\Manager;

use Vlabs\MediaBundle\MediaInterface;
use Vlabs\MediaBundle\Queuing\QueuingChain;
use Vlabs\MediaBundle\Tools\ExtensionGuesser;
use Imagine\Gd\Image;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Storage\StorageInterface;

class MediaManager implements MediaManagerInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var QueuingChain
     */
    protected $chain;

    /**
     * @var StorageInterface
     */
    protected $storage;

    public function __construct($config = [], QueuingChain $chain, StorageInterface $storage)
    {
        $this->config = $config;
        $this->chain = $chain;
        $this->storage = $storage;
    }

    /**
     * @inheritdoc
     */
    public function remapMedia(MediaInterface $media, PropertyMapping $mapping)
    {
        $file = $media->getMediaFile();

        $media->setPath($mapping->getUriPrefix());
        $media->setMimeType($file->getMimeType());
    }

    /**
     * @inheritdoc
     */
    public function publishResize(MediaInterface $media)
    {
        $queuing = $this->getQueuing($media);

        if(method_exists($queuing, 'setMediaManager')) {
            $queuing->setMediaManager($this);
        }

        foreach($this->config['resize'][$media->getKey()]['thumbs'] as $thumb => $config) {
            $queuing->enqueue($media, $thumb);
        }
    }

    /**
     * @inheritdoc
     */
    public function doResize(MediaInterface $media, $thumb)
    {
        $imageConfig = $this->config['resize'][$media->getKey()]['thumbs'][$thumb];
        $fsKey = $this->config['resize'][$media->getKey()]['filesystem'];
        $stream = $this->storage->resolveStream($media, 'mediaFile');

        $imagine = new Imagine();
        $image = $imagine->load(stream_get_contents($stream));

        $box = new Box($imageConfig['size']['width'], $imageConfig['size']['height']);

        if(isset($imageConfig['relative_resize'])){
            switch($imageConfig['relative_resize']) {
                case 'heighten':
                    $box = $image->getSize()->heighten($imageConfig['size']['height']);
                    break;
                case 'widen':
                    $box = $image->getSize()->widen($imageConfig['size']['width']);
                    break;
            }
        }

        $resizedImage = $image->thumbnail($box, $imageConfig['mode'])
                                ->get(ExtensionGuesser::guess($media->getMimeType()));

        $fs = $this->storage->getFileSystemFromKey($fsKey);
        $fs->write(sprintf('%s/%s', $imageConfig['uri_prefix'], $media->getFilename()), $resizedImage);
    }

    /**
     * @inheritdoc
     */
    public function getQueuing(MediaInterface $media)
    {
        $queuingAlias = $this->config['resize'][$media->getKey()]['queuing'];

        return $this->chain->getQueuing($queuingAlias);
    }

    /**
     * @inheritdoc
     */
    public function getUri(MediaInterface $media, $absolute = false, $thumb = null)
    {
        $mediaUri = "";

        if($absolute) $mediaUri.= isset($this->config['resize'][$media->getKey()]) ? $this->config['resize'][$media->getKey()]['base_url'] : $this->config['default_base_url'];

        $mediaUri.= $media->getPath();

        if($thumb) $mediaUri.= $this->config['resize'][$media->getKey()]['thumbs'][$thumb]['uri_prefix'];

        $mediaUri.= sprintf('/%s', $media->getFilename());

        return $mediaUri;
    }

    /**
     * @inheritdoc
     */
    public function hasMedia(MediaInterface $media, $thumb = false)
    {
        if (!isset($this->config['resize'][$media->getKey()])) return true;

        $fsKey = $this->config['resize'][$media->getKey()]['filesystem'];
        $fs = $this->storage->getFileSystemFromKey($fsKey);

        if ($thumb) {
            $urlPrefix = $this->config['resize'][$media->getKey()]['thumbs'][$thumb]['uri_prefix'];
            return $fs->has(sprintf('%s/%s', $urlPrefix, $media->getFilename()));
        }
        return $fs->has($media->getFilename());
    }

    /**
     * @inheritdoc
     */
    public function deleteMedia(MediaInterface $media)
    {
        $fsKey = $this->config['resize'][$media->getKey()]['filesystem'];
        $fs = $this->storage->getFileSystemFromKey($fsKey);

        if($fs->has($media->getFilename())) {
            $fs->delete($media->getFilename());
        }

        foreach($this->config['resize'][$media->getKey()]['thumbs'] as $config) {
            $filePath = sprintf('%s/%s', $config['uri_prefix'], $media->getFilename());
            if($fs->has($filePath)){
                $fs->delete($filePath);
            }
        }
    }
}
