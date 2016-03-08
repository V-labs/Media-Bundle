<?php

namespace Vlabs\MediaBundle;

use Symfony\Component\HttpFoundation\File\File;

interface MediaInterface
{
    /**
     * @param $path
     */
    public function setPath($path);

    /**
     * @return string
     */
    public function getPath();

    /**
     * @param $filename
     */
    public function setFilename($filename);

    /**
     * @return string
     */
    public function getFilename();

    /**
     * @param $mimeType
     */
    public function setMimeType($mimeType);

    /**
     * @return string
     */
    public function getMimeType();

    /**
     * @param File $media
     */
    public function setMediaFile(File $media = null);

    /**
     * @return File
     */
    public function getMediaFile();

    /**
     * Media key to handle custom actions
     *
     * @return string
     */
    public function getKey();
}
