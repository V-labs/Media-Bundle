<?php

namespace Vlabs\MediaBundle;

use Symfony\Component\HttpFoundation\File\File;

interface MediaInterface
{
    /**
     * @param string|null $path
     * @return MediaInterface|null
     */
    public function setPath(?string $path): ?MediaInterface;

    /**
     * @return string|null
     */
    public function getPath(): ?string;

    /**
     * @param string|null $filename
     * @return MediaInterface|null
     */
    public function setFilename(?string $filename): ?MediaInterface;

    /**
     * @return string|null
     */
    public function getFilename(): ?string;

    /**
     * @param string|null $mimeType
     * @return MediaInterface|null
     */
    public function setMimeType(?string $mimeType): ?MediaInterface;

    /**
     * @return string
     */
    public function getMimeType(): ?string;

    /**
     * @param File|null $mediaFile
     * @return MediaInterface|null
     */
    public function setMediaFile(File $mediaFile = null): ?MediaInterface;

    /**
     * @return File|null
     */
    public function getMediaFile(): ?File;

    /**
     * Media key to handle custom actions
     * @return string
     */
    public function getKey(): string;
}
