<?php
namespace Vlabs\MediaBundle\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Vlabs\MediaBundle\MediaInterface;

abstract class Media implements MediaInterface
{
    /** @var integer|null */
    protected $id;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @var File|null
     */
    protected $mediaFile;

    /**
     * @var string|null
     */
    private $path;

    /**
     * @var string|null
     */
    private $filename;

    /**
     * @var string|null
     */
    private $mimeType;

    /**
     * Get id
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    abstract function getKey(): string;

    /**
     * {@inheritdoc}
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function setPath($path): ?MediaInterface
    {
        $this->path = $path;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * {@inheritdoc}
     */
    public function setFilename(?string $filename): ?MediaInterface
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * {@inheritdoc}
     */
    public function setMimeType(?string $mimeType): ?MediaInterface
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMediaFile(): ?File
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
     * {@inheritdoc}
     */
    public function setMediaFile(?File $mediaFile = null): ?MediaInterface
    {
        $this->mediaFile = $mediaFile;

        return $this;
    }

    public function __clone()
    {
        $this->id = null;
        $this->setPath(null);
        $this->setMimeType(null);
        $this->setFilename(null);
    }
}
