<?php

namespace Vlabs\MediaBundle\Storage;

use League\Flysystem\FilesystemInterface;
use Vich\UploaderBundle\Storage\FlysystemStorage;

class Storage extends FlysystemStorage
{
    /**
     * @param string $key
     * @return FilesystemInterface
     */
    public function getFileSystemFromKey(string $key): FilesystemInterface
    {
        return $this->registry->getFilesystem($key);
    }
}
