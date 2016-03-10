<?php

namespace Vlabs\MediaBundle\Storage;

use Vich\UploaderBundle\Storage\FlysystemStorage;

class Storage extends FlysystemStorage
{
    public function getFileSystemFromKey($key)
    {
        return $this->mountManager->getFilesystem($key);
    }

    public function resolveStream($obj, $fieldName, $className = null)
    {
        $path = $this->resolvePath($obj, $fieldName, $className);
        if (empty($path)) {
            return;
        }
        $mapping = $this->factory->fromField($obj, $fieldName, $className);
        $fs = $this->getFilesystem($mapping);
        return $fs->readStream($path);
    }
}
