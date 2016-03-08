<?php

namespace Vlabs\MediaBundle\Storage;

use Vich\UploaderBundle\Storage\FlysystemStorage;

class Storage extends FlysystemStorage
{
    public function getFileSystemFromKey($key)
    {
        return $this->mountManager->getFilesystem($key);
    }
}
