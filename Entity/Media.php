<?php

namespace Vlabs\MediaBundle\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vlabs\MediaBundle\MediaInterface;

abstract class Media implements MediaInterface
{
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * @var File
     */
    protected $mediaFile;
    /**
     * @var integer
     */
    private $id;
    /**
     * @var string
     */
    private $path;
    /**
     * @var string
     */
    private $filename;
    /**
     * @var string
     */
    private $mimeType;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    function getKey()
    {
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Media
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return Media
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     *
     * @return Media
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * @return File
     */
    public function getMediaFile()
    {
        return $this->mediaFile;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|UploadedFile $mediaFile
     *
     * @return $this
     */
    public function setMediaFile(File $mediaFile = null)
    {
        $this->mediaFile = $mediaFile;

        return $this;
    }
}
